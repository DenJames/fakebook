@props(['post'])

<button id="dropdownMenuIconHorizontalButton-{{ $post->id }}" data-dropdown-toggle="dropdownDotsHorizontal-{{ $post->id }}" class="inline-flex items-center p-1 mt-1 text-sm font-medium text-center rounded-full hover:bg-gray-300 focus:ring-4 focus:outline-none dark:text-white focus:ring-gray-50" type="button">
    <svg class="w-5 h-5 text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 3">
        <path d="M2 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Zm6.041 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM14 0a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3Z"/>
    </svg>
</button>
<!-- Dropdown menu -->
<div id="dropdownDotsHorizontal-{{ $post->id }}" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-20 overflow-hidden">
    <ul class="text-sm text-gray-700" aria-labelledby="dropdownMenuIconHorizontalButton-{{ $post->id }}">
        <li>
            <button type="submit" class="block px-4 py-2 hover:bg-gray-100 w-full edit-post" data-post-id="{{ $post->id }}">Edit</button>
        </li>
        <li>
            <button type="submit" class="block px-4 py-2 hover:bg-red-500 hover:text-white w-full delete-post confirm" data-post-id="{{ $post->id }}">Delete</button>
        </li>
    </ul>
</div>
