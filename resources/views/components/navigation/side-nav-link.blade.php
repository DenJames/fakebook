@props(['href' => '#', 'active' => false])


<li>
    <a href="{{ $href }}"
       @class([
            'group flex gap-x-3 rounded p-2 text-sm leading-6 font-semibold text-blue-100 hover:bg-blue-700 transition duration-150 ease-in-out',
            'text-indigo-600 bg-gray-50' => $active,
        ])>
        {{ $icon }}

        <span>
            {{ $slot }}
        </span>
    </a>
</li>
