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
                    Edit post
                </h3>
            </div>
            <!-- Modal body -->

            <form action="{{ route('posts.update', $post) }}" method="post" class="dropzone" id="post-update">
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

                    <livewire:tip-tap-editor :post="$post" editor-id="timeline-textarea-status-edit" />

                    <div class="flex flex-col timeline-image-container" id="timeline-image-container">
{{--                        <div class="items-center justify-center w-full">--}}
{{--                            <div class="flex flex-col items-center justify-center pt-5 pb-6 no-images-uploaded hover:bg-gray-800 rounded-xl">--}}
{{--                                <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">--}}
{{--                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>--}}
{{--                                </svg>--}}
{{--                                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>--}}
{{--                                <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF</p>--}}
{{--                            </div>--}}
{{--                        </div>--}}

                        <div class="image-preview-div-updated grid grid-cols-3 gap-2 max-h-64 overflow-y-auto"></div>

                        <div class="hidden">
                            <div class="preview-template">
                                <div class="dz-preview dz-file-preview">
                                    <div class="dz-details">
                                        <img data-dz-thumbnail class="w-full h-[100px] bg-contain" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="items-center justify-center w-full">
                            <div class="flex flex-col items-center justify-center pt-1 pb-1 hover:bg-gray-800 rounded-xl bg-gray-900 update-post-images">
                                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF</p>
                            </div>
                        </div>

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
