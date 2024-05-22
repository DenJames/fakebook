<x-app-layout>
    <x-form.primary-button>
        <a href="{{ route('support.tickets.create') }}">
            Create ticket
        </a>
    </x-form.primary-button>

    <div class="grid grid-cols-2 gap-4 mt-4">
        <div class="flex flex-col gap-2 max-h-screen overflow-y-auto col-span-2 lg:col-span-1">
            <h2 class="text-2xl">Open tickets</h2>

            @foreach($tickets as $ticket)
                <a href="{{ route('profile.show', $friend) }}" class="w-full">
                    <x-content.card content-classes="border flex items-center gap-4 w-full">
                        {{ $ticket->subject }}
                    </x-content.card>
                </a>
            @endforeach

            <div class="w-full lg:w-1/2 mt-4">
                {{ $tickets->links() }}
            </div>
        </div>

        <div class="flex flex-col gap-2 max-h-screen overflow-y-auto col-span-2 lg:col-span-1">
            <h2 class="text-2xl">Solved tickets</h2>

            @foreach($solvedTickets as $ticket)
                <a href="{{ route('profile.show', $ticket) }}" class="w-full">
                    <x-content.card content-classes="border flex items-center gap-4 w-full">
                        {{ $ticket->subject }}
                    </x-content.card>
                </a>
            @endforeach

            <div class="w-full lg:w-1/2 mt-4">
                {{ $solvedTickets->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
