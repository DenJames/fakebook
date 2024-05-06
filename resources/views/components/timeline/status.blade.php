<div class="rounded-lg bg-gray-100">
    <div class="flex flex-row gap-2 p-3">
        <img src="{{ asset(Auth::user()->profile_photo) }}" alt="profile photo" class="rounded-full h-[40px] w-[40px]">
        <x-text-input id="timeline_status_input" type="text" placeholder="What's on your mind, {{ Auth::user()->name }}?" class="w-full rounded-full" data-modal-target="default-modal" data-modal-toggle="default-modal" />
    </div>
</div>
<x-timeline.status-modal modal-id="default-modal"/>

@vite('resources/js/timeline.js')
