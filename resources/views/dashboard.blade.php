<x-app-layout>
    @push('title', 'Timeline')

    <div class="overflow-hidden shadow-sm sm:rounded-lg">
        <div class="text-gray-900 ">
            <x-timeline.status/>
            <div class="flex flex-col gap-4 posts-container">
                @foreach($posts as $post)
                    <livewire:post :post="$post" :key="$post->id"/>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
