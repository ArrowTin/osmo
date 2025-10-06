@props(['active' => false])

<a {{ $attributes->merge([
        'class' => 'flex items-center space-x-2 p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700 ' .
                   ($active ? 'bg-gray-200 dark:bg-gray-700 text-blue-600 dark:text-blue-400 font-semibold'
                            : 'text-gray-700 dark:text-gray-300')
    ]) }}
>
    {{ $slot }}
</a>