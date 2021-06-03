<?php

namespace Ydm\Datatables\Traits;

trait WithBulkActions
{
    public $bulkActions = [];
    public $primaryKey = 'id';
    public $selectPage = false;
    public $selectAll = false;
    public $selected = [];

    public function getSelectedKeysProperty(): array
    {
        return $this->selectedKeys();
    }

    public function getSelectedRowsQueryProperty()
    {
        return $this->selectedRowsQuery();
    }

    public function renderingWithBulkActions(): void
    {
        if ($this->selectAll) {
            $this->selectPageRows();
        }
    }

    public function selectedKeys(): array
    {
        return $this->selectedRowsQuery()->pluck($this->rowsQuery()->qualifyColumn($this->primaryKey))->toArray();
    }

    public function selectAll(): void
    {
        $this->selectAll = true;
    }

    public function selectPageRows(): void
    {
        $this->selected = $this->rows->pluck($this->primaryKey)->map(fn($key) => (string)$key);
    }

    public function selectedRowsQuery()
    {
        return (clone $this->rowsQuery())
            ->unless($this->selectAll, function ($query) {
                return $query->whereIn($query->qualifyColumn($this->primaryKey), $this->selected);
            });
    }

    public function resetBulk(): void
    {
        $this->selectPage = false;
        $this->selectAll = false;
        $this->selected = [];
    }

    public function updatedSelected(): void
    {
        $this->selectAll = false;
        $this->selectPage = false;
    }

    public function updatedSelectPage($value): void
    {
        if ($value) {
            $this->selectPageRows();

            return;
        }

        $this->selectAll = false;
        $this->selected = [];
    }
}
