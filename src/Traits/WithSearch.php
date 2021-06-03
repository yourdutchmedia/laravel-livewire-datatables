<?php

namespace Ydm\Datatables\Traits;

trait WithSearch
{
    public $searchFilterDebounce = null;
    public $searchFilterDefer = null;
    public $searchFilterLazy = null;
    public $showSearch = true;

    public function getSearchFilterOptionsProperty(): string
    {
        if ($this->searchFilterDebounce) {
            return '.debounce.' . $this->searchFilterDebounce . 'ms';
        }

        if ($this->searchFilterDefer) {
            return '.defer';
        }

        if ($this->searchFilterLazy) {
            return '.lazy';
        }

        return '';
    }

    public function resetSearch(): void
    {
        $this->filters['search'] = null;
    }
}
