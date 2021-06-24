<?php

namespace Ydm\Datatables;

use Livewire\Component;
use Ydm\Datatables\Traits\WithBulkActions;
use Ydm\Datatables\Traits\WithCustomPagination;
use Ydm\Datatables\Traits\WithFilters;
use Ydm\Datatables\Traits\WithPerPagePagination;
use Ydm\Datatables\Traits\WithSearch;
use Ydm\Datatables\Traits\WithSorting;

abstract class DatatableComponent extends Component
{
    use WithBulkActions,
        WithCustomPagination,
        WithFilters,
        WithPerPagePagination,
        WithSearch,
        WithSorting;

    public $emptyMessage = 'No items found. Try narrowing your search.';
    public $offlineIndicator = true;
    public $paginationTheme = 'tailwind';
    public $refresh = false;

    protected $listeners = ['refreshDatatable' => '$refresh'];
    protected $pageName = 'page';
    protected $queryString = [
        'filters' => ['except' => null],
        'sorts' => ['except' => null],
    ];
    protected $tableName = 'table';

    public function __construct($id = null)
    {
        parent::__construct($id);

        $this->filters = array_merge($this->filters, $this->baseFilters);
    }

    protected function getColumn(string $column)
    {
        return collect($this->columns())
            ->where('column', $column)
            ->first();
    }

    public function getRowsProperty()
    {
        if ($this->paginationEnabled) {
            return $this->applyPagination($this->rowsQuery());
        }

        return $this->rowsQuery()->get();
    }

    public function render()
    {
        return view('livewire-datatables::datatable')
            ->with([
                'columns' => $this->columns(),
                'rowView' => $this->rowView(),
                'filtersView' => $this->filtersView(),
                'customFilters' => $this->filters(),
                'rows' => $this->rows,
            ]);
    }

    public function refreshAttribute(): string
    {
        if (is_numeric($this->refresh)) {
            return 'wire:poll.' . $this->refresh . 'ms';
        } elseif (is_string($this->refresh)) {
            if ($this->refresh === '.keep-alive' || $this->refresh === 'keep-alive') {
                return 'wire:poll.keep-alive';
            } elseif ($this->refresh === '.visible' || $this->refresh === 'visible') {
                return 'wire:poll.visible';
            }

            return 'wire:poll="' . $this->refresh . '"';
        }

        return '';
    }

    public function resetAll(): void
    {
        $this->resetFilters();
        $this->resetSearch();
        $this->resetSorts();
        $this->resetBulk();
        $this->resetPage();
    }

    public function rowsQuery()
    {
        $this->cleanFilters();

        $query = $this->query();

        if (method_exists($this, 'applySorting')) {
            $query = $this->applySorting($query);
        }

        if (method_exists($this, 'applySearchFilter')) {
            $query = $this->applySearchFilter($query);
        }

        return $query;
    }

    public function rowView(): string
    {
        return 'livewire-datatables::includes.table-row-columns';
    }

    abstract public function columns(): array;

    abstract public function query();

}
