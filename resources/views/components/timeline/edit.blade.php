@props([
    'post',
    'modalId' => 'default-modal'
])

<div id="{{ $modalId }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 max-h-full bg-opacity-50 bg-black edit-post-modal">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="relative text-xl font-semibold text-gray-900 dark:text-white text-center w-full">
                    Create post
                </h3>
            </div>
            <!-- Modal body -->
            <form action="{{ route('posts.update', $post) }}" method="post" id="post-update">
                @csrf
                <div class="p-4 md:p-5 space-y-4">
                    <div class="flex flex-row gap-2">
                        <img src="{{ asset(Auth::user()->profile_photo) }}" alt="profile photo" class="w-10 h-10 rounded-full">
                        <div>
                            <p class="text-gray-900 dark:text-white font-semibold">{{ Auth::user()->name }}</p>
                            <select id="visibility" name="visibility" class="bg-gray-600 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full py-0 pl-1">
                                <option {{ $post->visibility == 'private' ? 'selected' : '' }} value="private">Private</option>
                                <option {{ $post->visibility == 'friends' ? 'selected' : '' }} value="friends">Friends</option>
                                <option {{ $post->visibility == 'public' ? 'selected' : '' }} value="public">Public</option>
                            </select>
                        </div>
                    </div>
                    <div x-data="editor('')" class="text-white">

                        <template x-if="isLoaded()">
                            <div class="menu flex items-center gap-2">
                                <button @click="toggleHeading({ level: 1 })" :class="{ 'is-active': isActive('heading', { level: 1 }, updatedAt) }" type="button">
                                    <div class="text-white">
                                        <x-icons.h1 width="w-[15px]"/>
                                    </div>
                                </button>
                                <button @click="toggleHeading({ level: 2 })" :class="{ 'is-active': isActive('heading', { level: 2 }, updatedAt) }" type="button">
                                    <div class="text-white">
                                        <x-icons.h2 width="w-[15px]"/>
                                    </div>
                                </button>
                                <button @click="toggleHeading({ level: 3 })" :class="{ 'is-active': isActive('heading', { level: 3 }, updatedAt) }" type="button">
                                    <div class="text-white">
                                        <x-icons.h3 width="w-[15px]"/>
                                    </div>
                                </button>
                                <button @click="toggleHeading({ level: 4 })" :class="{ 'is-active': isActive('heading', { level: 4 }, updatedAt) }" type="button">
                                    <div class="text-white">
                                        <x-icons.h4 width="w-[15px]"/>
                                    </div>
                                </button>
                                <button @click="toggleHeading({ level: 5 })" :class="{ 'is-active': isActive('heading', { level: 5 }, updatedAt) }" type="button">
                                    <div class="text-white">
                                        <x-icons.h5 width="w-[15px]"/>
                                    </div>
                                </button>
                                <button @click="toggleHeading({ level: 6 })" :class="{ 'is-active': isActive('heading', { level: 6 }, updatedAt) }" type="button">
                                    <div class="text-white">
                                        <x-icons.h6 width="w-[15px]"/>
                                    </div>
                                </button>
                                <button
                                    @click="toggleBold()"
                                    :class="{ 'is-active' : isActive('bold', updatedAt) }"
                                    type="button"
                                >
                                    <x-icons.bold width="w-3" height="h-3"/>
                                </button>
                                <button
                                    @click="toggleItalic()"
                                    :class="{ 'is-active' : isActive('italic', updatedAt) }"
                                    type="button"
                                >
                                    <x-icons.italic width="w-3" height="h-3"/>
                                </button>
                            </div>
                        </template>

                        <div x-ref="element" id="timeline-textarea-status-edit" class="pt-1">

                        </div>

                        <div class="innterHTML" id="editor-text">{!! str_replace('<br class="ProseMirror-trailingBreak">', '', $post->content) !!}</div>
                    </div>
                </div>
            </form>
            <!-- Modal footer -->
            <div class="flex items-center gap-5 p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                <button id="post_update" data-modal-hide="{{ $modalId }}" type="submit" form="post-update" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 w-full">Update</button>


                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white modal-close" data-modal-hide="{{ $modalId }}">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Cancel</span>
                </button>
            </div>
        </div>
    </div>
</div>
