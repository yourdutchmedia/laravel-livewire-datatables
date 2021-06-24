@foreach($columns as $column)
    @if ($column->isVisible())
        <x-ydm-table-td>
            @if ($column->html)
                {{ new \Illuminate\Support\HtmlString($column->formatted($row)) }}
            @else
                {{ $column->formatted($row) }}
            @endif
        </x-ydm-table-td>
    @endif
@endforeach
