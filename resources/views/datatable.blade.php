<div {!! $this->refreshAttribute() !!}>

    @include('livewire-datatables::datatable-offline-message')

    <div class="flex-col space-y-4">
        {{-- Active Sorting pills --}}
        @include('livewire-datatables::includes.active-filter-pills')

        {{-- Active Filter pills --}}
        @include('livewire-datatables::includes.active-sorting-pills')

        <div class="md:flex md:justify-between p-6 md:p-0">
            <div class="w-full mb-4 md:mb-0 md:w-2/4 md:flex space-y-4 md:space-y-0 md:space-x-4">
                {{-- Search --}}
                @include('livewire-datatables::includes.search')

                {{-- Filters --}}
                @include('livewire-datatables::includes.filter')
            </div>

            <div class="md:space-x-2 md:flex md:items-center">
                {{-- Bulk actions --}}
                @include('livewire-datatables::includes.bulk-actions')

                {{-- Per page --}}
                @include('livewire-datatables::includes.per_page')
            </div>
        </div>


        @include('livewire-datatables::includes.table')
        @include('livewire-datatables::includes.pagination')
    </div>
</div>
