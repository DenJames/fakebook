<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 w-full">
    <div class="space-y-6"  x-data="{ editing: false }">
        <x-content.card>
            <div action="{{ route('support.tickets.store') }}" method="POST" class="grid grid-cols-2 gap-4 w-full">
                <div class="flex flex-col">
                    <label for="category">
                        Category
                    </label>

                    <select name="" id="" class="rounded-md border border-gray-200 disabled:bg-gray-200" disabled>
                        <option selected>
                            {{ $ticket->category->name }}
                        </option>
                    </select>
                </div>

                <div class="flex flex-col">
                    <label for="subject">
                        Subject
                    </label>

                    <input id="subject" name="subject" value="{{ $ticket->subject }}"
                           class="rounded-md border border-gray-200 disabled:bg-gray-200" disabled/>
                </div>

                <div class="col-span-2 flex flex-col">
                    <label for="description" class="font-bold">
                        Description
                    </label>

                    <template x-if="!editing">
                        <article class="text-pretty py-3 px-4 bg-gray-100 rounded-md">
                            {{ $ticket->content }}
                        </article>
                    </template>

                    <template x-if="editing">
                        <textarea id="description" name="content"
                                  class="rounded-md border border-gray-200 min-h-64" wire:model="content">
                        </textarea>
                    </template>
                </div>

                <div class="col-span-2 w-full flex justify-end gap-4" x-cloak>
                    @if($ticket->closed_at)
                        <x-form.warning-button wire:click.prevent="toggleTicketStatus" @click="editing = false" wire:key="reopen">
                            Re-open Ticket
                        </x-form.warning-button>
                    @else
                        <x-form.primary-button @click="editing = true" x-show="!editing" wire:key="edit">
                            Edit ticket
                        </x-form.primary-button>

                        <x-form.primary-button @click="editing = false" x-show="editing" wire:click="updateTicket" wire:key="save">
                            Save ticket
                        </x-form.primary-button>

                        <x-form.warning-button @click="editing = false" x-show="editing" wire:key="cancel">
                            Cancel edit
                        </x-form.warning-button>

                        <x-danger-button wire:click.prevent="toggleTicketStatus" x-show="!editing" @click="editing = false" wire:key="close">
                            Close Ticket
                        </x-danger-button>
                    @endif
                </div>
            </div>
        </x-content.card>

        @if(!$this->ticket->closed_at)
            <x-content.card>
                <div class="flex flex-col gap-4 w-full">
                    <form wire:submit.prevent="postReply"
                          class="grid grid-cols-2 gap-4 w-full">

                        <div class="col-span-2 flex flex-col">
                            <label for="content">
                                Reply
                            </label>

                            <textarea id="content" name="content" placeholder="Reply to this ticket"
                                      class="rounded-md border border-gray-200" wire:model="reply"></textarea>
                        </div>

                        <div class="col-span-2">
                            <x-form.primary-button>
                                Post Reply
                            </x-form.primary-button>
                        </div>
                    </form>
                </div>
            </x-content.card>
        @endif
    </div>

    <livewire:support.ticket-replies :ticket="$ticket"/>
</div>

@script
<script>
    $wire.on('replyPosted', () => {
        document.getElementById('content').value = '';
    });
</script>
@endscript
