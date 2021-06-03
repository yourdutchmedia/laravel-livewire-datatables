<?php

namespace Ydm\Datatables\Traits;

use Illuminate\Database\Eloquent\Builder;
use Ydm\Datatables\Support\Column;

trait WithFilters
{
    public $baseFilters = [
        'search' => null,
    ];
    public $filters = [];
    public $filterNames = [];
    public $showFilterDropdown = true;
    public $showFilters = true;

    public function applySearchFilter($query)
    {
        $searchableColumns = $this->getSearchableColumns();
        if ($this->hasFilter('search') && count($searchableColumns)) {
            $search = $this->getFilter('search');

            $query->where(function (Builder $subQuery) use ($search, $query, $searchableColumns) {
                foreach ($searchableColumns as $column) {
                    $hasRelation = Column::hasRelation($column->column());
                    $selectedColumn = Column::mapToSelected($column->column(), $query);

                    if ($column->hasSearchCallback()) {
                        ($column->getSearchCallback())($subQuery, $search);
                    } elseif (!$hasRelation || $selectedColumn) {
                        $whereColumn = $selectedColumn ?? $column->column();
                        if (!$hasRelation) {
                            $whereColumn = $query->getModel()->getTable() . '.' . $whereColumn;
                        }

                        $subQuery->orWhere($whereColumn, 'like', '%' . $search . '%');
                    } else {
                        $relationName = Column::parseRelation($column->column());
                        $fieldName = Column::parseField($column->column());

                        $subQuery->orWhereHas($relationName, function (Builder $hasQuery) use ($fieldName, $search) {
                            $hasQuery->where($fieldName, 'like', '%' . $search . '%');
                        });
                    }
                }
            });
        }

        return $query;
    }

    public function checkFilters(): void
    {
        foreach ($this->filters() as $filter => $_default) {
            if (!isset($this->filters[$filter]) || $this->filters[$filter] === '') {
                $this->filters[$filter] = null;
            }
        }
    }

    public function cleanFilters(): void
    {
        $this->filters = collect($this->filters)->filter(function ($filterValue, $filterName) {
            $filterDefinitions = $this->filters();
            if ($filterName === 'search') {
                return true;
            }

            if (!isset($filterDefinitions[$filterName])) {
                return false;
            }

            if (is_null($filterValue)) {
                return true;
            }

            if ($filterDefinitions[$filterName]->isSelect()) {
                foreach ($this->getFilterOptions($filterName) as $optionValue) {
                    if (is_int($optionValue) && $optionValue === (int)$filterValue) {
                        return true;
                    }

                    if ($optionValue === $filterValue) {
                        return true;
                    }
                }
            }

            return false;
        })->toArray();
    }

    public function filters(): array
    {
        return [];
    }

    public function filtersView(): ?string
    {
        return null;
    }

    public function getFilter(string $filter)
    {
        if ($this->hasFilter($filter)) {
            if (in_array($filter, collect($this->filters())->keys()->toArray(), true) && $this->filters()[$filter]->isSelect()) {
                return $this->hasIntegerKeys($filter) ? (int)$this->filters[$filter] : trim($this->filters[$filter]);
            }

            return trim($this->filters[$filter]);
        }

        return null;
    }

    public function getFilterOptions(string $filter): array
    {
        return collect($this->filters()[$filter]->options())
            ->keys()
            ->reject(function ($item) {
                return $item === '' || $item === null;
            })
            ->values()
            ->toArray();
    }

    public function getFilters(): array
    {
        return collect($this->filters)
            ->reject(function ($value) {
                return $value === null || $value === '';
            })
            ->toArray();
    }

    public function getFiltersWithoutSearch(): array
    {
        return collect($this->getFilters())
            ->reject(function ($_value, $key) {
                return $key === 'search';
            })
            ->toArray();
    }

    public function getSearchableColumns(): array
    {
        return array_filter($this->columns(), function (Column $column) {
            return $column->isSearchable();
        });
    }

    public function hasFilter(string $filter): bool
    {
        return isset($this->filters[$filter]) && $this->filters[$filter] !== null && $this->filters[$filter] !== '';
    }

    public function hasIntegerKeys(string $filter): bool
    {
        return is_int($this->getFilterOptions($filter)[0] ?? null);
    }

    public function mountWithFilters(): void
    {
        $this->checkFilters();
    }

    public function removeFilter($filter): void
    {
        if (isset($this->filters[$filter])) {
            $this->filters[$filter] = null;
        }
    }

    public function resetFilters(): void
    {
        $search = $this->filters['search'] ?? null;

        $this->reset('filters');

        $this->filters['search'] = $search;
    }

    public function updatedFilters(): void
    {
        if (isset($this->filters['search']) && $this->filters['search'] === '') {
            $this->resetSearch();
        }

        $this->checkFilters();

        $this->resetPage();
    }
}
