<div>
    <x-content.card>
        <div class="px-4 sm:px-6 lg:px-8 w-full">
            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-base font-semibold leading-6 text-gray-900">Tickets</h1>
                </div>

                <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                    <select class="rounded-md border border-gray-200 text-sm" name="ticket-status" id="ticket-status" wire:model="ticketStatus" wire:change="changeStatus">
                        <option value="open" selected>Open</option>
                        <option value="closed">Closed</option>
                    </select>

                    <x-form.primary-button class="lg:ml-2">
                        <a href="{{ route('support.tickets.create') }}">
                            Open ticket
                        </a>
                    </x-form.primary-button>
                </div>
            </div>

            <div class="mt-8 flow-root">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8 max-h-[800px]">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead>
                            <tr>
                                <th scope="col"
                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">
                                    Subject
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Opened
                                    by
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Latest
                                    update
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Latest
                                    reply from
                                </th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                            @foreach($tickets as $ticket)
                                <tr>
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">
                                        {{ $ticket->subject }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        {{ $ticket->user->name }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        {{ $ticket->updated_at->diffForHumans() }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        N/A
                                    </td>

                                    <td class="flex gap-x-2 whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        <a href="{{ route('support.tickets.show', $ticket) }}"
                                           class="text-indigo-600 hover:text-indigo-900"><x-icons.eye /><span
                                                class="sr-only">, {{ $ticket->subject }}</span></a>

                                        <form action="{{ route('support.tickets.destroy', $ticket) }}">
                                            @csrf
                                            @method('DELETE')

                                            <button type="button" class="text-indigo-600 hover:text-indigo-900" onclick="if(confirm('Are you sure you want to delete this support ticket?')) this.form.submit();"><x-icons.trash /><span
                                                    class="sr-only">, {{ $ticket->subject }}</span></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </x-content.card>
</div>
