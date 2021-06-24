@if ($showSearch)
    <div class="flex rounded-md shadow-sm">
        <input class="flex-1 shadow-sm border-gray-300 block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 focus:outline-none @if (isset($filters['search']) && strlen($filters['search'])) rounded-none rounded-l-md @else rounded-md @endif"
               placeholder="{{ __('Search') }}"
               type="text"
               wire:model{{ $this->searchFilterOptions }}="filters.search"/>

        @if (isset($filters['search']) && strlen($filters['search']))
            <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 cursor-pointer sm:text-sm"
                    wire:click="$set('filters.search', null)">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </span>
        @endif
    </div>
@endif
