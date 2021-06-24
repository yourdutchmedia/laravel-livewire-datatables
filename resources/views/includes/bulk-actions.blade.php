@if (count($bulkActions))
    <div class="w-full md:w-auto mb-4 md:mb-0">
        <div class="relative inline-block text-left z-10 w-full md:w-auto"
             @keydown.window.escape="open = false"
             @click.away="open = false"
             x-data="{ open: false }">
            <div>
                <span class="rounded-md shadow-sm">
                    <button class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-indigo-300 focus:shadow-outline-indigo active:bg-gray-50 active:text-gray-800 transition ease-in-out duration-150"
                            aria-haspopup="true"
                            aria-expanded="true"
                            id="options-menu"
                            type="button"
                            @click="open = !open"
                            x-bind:aria-expanded="open">
                        @lang('Bulk Actions')

                        <svg class="-mr-1 ml-2 h-5 w-5" x-description="Heroicon name: chevron-down"
                             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                  clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </span>
            </div>

            <div class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg z-50"
                 x-cloak
                 x-show="open"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95">
                <div class="rounded-md bg-white shadow-xs">
                    <div class="py-1"
                         aria-orientation="vertical"
                         aria-labelledby="options-menu"
                         role="menu">
                        @foreach($bulkActions as $action => $title)
                            <button class="block w-full px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900 flex items-center space-x-2"
                                    type="button"
                                    role="menuitem"
                                    wire:click="{{ $action }}"
                                    wire:key="bulk-action-{{ $action }}">
                                <span>{{ $title }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
