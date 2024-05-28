@props([
    'modalId' => 'default-modal'
])

<!-- Main modal -->
<div id="{{ $modalId }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 max-h-full bg-opacity-50 bg-black">
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
            <form action="{{ route('posts.store') }}" method="post" class="dropzone" id="image-form">
                @csrf
                <div class="p-4 md:p-5 space-y-4">
                    <div class="flex flex-row gap-2">
                        <img src="{{ asset(Auth::user()->profile_photo) }}" alt="profile photo" class="w-10 h-10 rounded-full">
                        <div>
                            <p class="text-gray-900 dark:text-white font-semibold">{{ Auth::user()->name }}</p>
                            <select id="visibility" name="visibility" class="bg-gray-600 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full py-0 pl-1">
                                <option selected value="private">Private</option>
                                <option value="friends">Friends</option>
                                <option value="public">Public</option>
                            </select>
                        </div>
                    </div>

                    <livewire:tip-tap-editor />


{{--                    <textarea name="content" id="timeline-textarea-status" class="w-full h-min p-2 border border-gray-200 rounded-lg focus:ring-0 focus:border-none block sm:text-sm bg-transparent border-none outline-none text-white placeholder-gray-200 resize-none" placeholder="What's on your mind, {{ Auth::user()->name }}?"></textarea>--}}
                    <div class="flex flex-col timeline-image-container hidden" id="timeline-image-container">
                        <div class="items-center justify-center w-full">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6 no-images-uploaded hover:bg-gray-800 rounded-xl">
                                <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                </svg>
                                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (MAX. 800x400px)</p>
                            </div>
                        </div>

                        <div class="image-preview-div grid grid-cols-3 gap-2 max-h-64 overflow-y-auto">

                        </div>

                        <div class="hidden">
                            <div class="preview-template">
                                <div class="dz-preview dz-file-preview">
                                    <div class="dz-details">
                                        <img data-dz-thumbnail class="w-full h-[100px] bg-contain" />
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="border-2 border-gray-600 flex flex-row justify-between text-white px-3 py-2 rounded-xl justify-center align-middle">
                        <div class="pt-1">Add some more to your post</div>
                        <div class="flex flex-row gap-3">
                            <div class="text-green-600 rounded-full p-1 hover:bg-gray-200 hover:bg-opacity-30 timeline-image-button">
                                <x-icons.image/>
                            </div>
                            <div class="text-blue-600 rounded-full p-1 hover:bg-gray-200 hover:bg-opacity-30 timeline-tag-button">
                                <x-icons.tag/>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <!-- Modal footer -->
            <div class="flex items-center gap-5 p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                <button data-modal-hide="default-modal" id="post_submit" type="submit" form="image-form" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 w-full">Post</button>


                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="default-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
{{--                <button data-modal-hide="default-modal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Decline</button>--}}
            </div>
        </div>
    </div>
</div>
