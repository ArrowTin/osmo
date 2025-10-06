@props(['id', 'keys' => [], 'columns' => []])

<div class="card p-4 bg-white dark:bg-gray-800 rounded shadow overflow-x-auto">
    <table id="{{ $id }}" 
           class="min-w-[1000px] text-sm dark:text-white" 
           data-keys="{{ json_encode($keys) }}">
        <thead>
            @if(!empty($columns) && is_array($columns))
                <tr>
                    @foreach($columns as $col)
                        <th class="px-4 py-2 text-left">{{ $col }}</th>
                    @endforeach
                </tr>
            @endif
        </thead>
        <tbody></tbody>
    </table>
</div>
