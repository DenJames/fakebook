<x-app-layout>
    @push('title', $ticket->subject)

    <div class="w-full flex justify-center">
        <livewire:support.ticket :ticket="$ticket"/>
    </div>
</x-app-layout>
