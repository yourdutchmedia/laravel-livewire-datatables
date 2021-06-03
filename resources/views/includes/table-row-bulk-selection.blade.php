@if (count($bulkActions) && (($selectPage && $rows->total() > $rows->count()) || count($selected)))
    <x-ydm-table-tr class="bg-indigo-50"
                    wire:key="row-message">
        <x-ydm-table-td :colspan="count($bulkActions) ? count($columns) + 1 : count($columns)">
            <div>
                @if (count($selected) && !$selectAll && !$selectPage)
                    <span>
                        @lang('You have selected')
                        <strong>{{ count($selected) }}</strong>
                        {{ count($selected) === 1 ? __('row') : __('rows')  }}.
                    </span>

                    <button class="ml-1 text-blue-600 underline text-gray-700 text-sm leading-5 font-medium focus:outline-none focus:text-gray-800 focus:underline transition duration-150 ease-in-out"
                            type="button"
                            wire:click="resetBulk"
                            wire:loading.attr="disabled">
                        @lang('Unselect All')
                    </button>
                @elseif ($selectAll)
                    <span>
                        @lang('You are currently selecting all')
                        <strong>{{ number_format($rows->total()) }}</strong>
                        @lang('rows').
                    </span>

                    <button class="ml-1 text-blue-600 underline text-gray-700 text-sm leading-5 font-medium focus:outline-none focus:text-gray-800 focus:underline transition duration-150 ease-in-out"
                            type="button"
                            wire:click="resetBulk"
                            wire:loading.attr="disabled">
                        @lang('Unselect All')
                    </button>
                @else
                    @if ($rows->total() === count($selected))
                        <span>
                            @lang('You have selected')
                            <strong>{{ count($selected) }}</strong>
                            {{ count($selected) === 1 ? __('row') : __('rows')  }}.
                        </span>

                        <button class="ml-1 text-blue-600 underline text-gray-700 text-sm leading-5 font-medium focus:outline-none focus:text-gray-800 focus:underline transition duration-150 ease-in-out"
                                type="button"
                                wire:click="resetBulk"
                                wire:loading.attr="disabled">
                            @lang('Unselect All')
                        </button>
                    @else
                        <span>
                            @lang('You have selected')
                            <strong>{{ $rows->count() }}</strong>
                            @lang('rows, do you want to select all')
                            <strong>{{ number_format($rows->total()) }}</strong>?
                        </span>

                        <button class="ml-1 text-blue-600 underline text-gray-700 text-sm leading-5 font-medium focus:outline-none focus:text-gray-800 focus:underline transition duration-150 ease-in-out"
                                type="button"
                                wire:click="selectAll"
                                wire:loading.attr="disabled">
                            @lang('Select All')
                        </button>

                        <button class="ml-1 text-blue-600 underline text-gray-700 text-sm leading-5 font-medium focus:outline-none focus:text-gray-800 focus:underline transition duration-150 ease-in-out"
                                type="button"
                                wire:click="resetBulk"
                                wire:loading.attr="disabled">
                            @lang('Unselect All')
                        </button>
                    @endif
                @endif
            </div>
        </x-ydm-table-td>
    </x-ydm-table-tr>
@endif
