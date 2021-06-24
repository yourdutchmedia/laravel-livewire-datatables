<?php

namespace Ydm\Datatables\Traits;

trait WithPerPagePagination
{
    public $showPagination = true;
    public $showPerPage = true;
    public $paginationEnabled = true;
    public $perPage = 25;
    public $perPageAll = false;
    public $perPageAccepted = [10, 25, 50, 100];

    public function applyPagination($query)
    {
        return $query->paginate($this->perPage === -1 ? $query->count() : $this->perPage, ['*'], $this->pageName());
    }

    public function mountWithPerPagePagination(): void
    {
        if ($this->perPageAll) {
            $this->perPageAccepted[] = -1;
        }

        if (in_array(session()->get($this->tableName . '-perPage', $this->perPage), $this->perPageAccepted, true)) {
            $this->perPage = session()->get($this->tableName . '-perPage', $this->perPage);
        } else {
            $this->perPage = $this->perPageAccepted[0] ?? 10;
        }
    }

    public function updatedPerPage($value): void
    {
        if (in_array(session()->get($this->tableName . '-perPage', $this->perPage), $this->perPageAccepted, true)) {
            session()->put($this->tableName . '-perPage', (int)$value);
        } else {
            session()->put($this->tableName . '-perPage', $this->perPageAccepted[0] ?? 10);
        }

        $this->resetPage();
    }
}
