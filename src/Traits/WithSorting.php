<?php

namespace Ydm\Datatables\Traits;

trait WithSorting
{
    public $singleColumnSorting = false;
    public $showSorting = true;
    public $sortNames = [];
    public $sortDirectionNames = [];
    public $sorts = [];

    public function applySorting($query)
    {
        foreach ($this->sorts as $field => $direction) {
            if (optional($this->getColumn($field))->hasSortCallback()) {
                $query = app()->call($this->getColumn($field)->getSortCallback(), ['query' => $query, 'direction' => $direction]);
            } else {
                $query->orderBy($field, $direction);
            }
        }

        return $query;
    }

    public function removeSort(string $field): void
    {
        if (isset($this->sorts[$field])) {
            unset($this->sorts[$field]);
        }
    }

    public function resetSorts(): void
    {
        $this->sorts = [];
    }

    public function sortBy(string $field): ?string
    {
        if ($this->singleColumnSorting && count($this->sorts) && !isset($this->sorts[$field])) {
            $this->sorts = [];
        }

        if (!isset($this->sorts[$field])) {
            return $this->sorts[$field] = 'asc';
        }

        if ($this->sorts[$field] === 'asc') {
            return $this->sorts[$field] = 'desc';
        }

        unset($this->sorts[$field]);

        return null;
    }
}
