<x-app-layout>
    <div class="w-full flex justify-center">
        <div class="max-w-4xl grid grid-cols-1 gap-4">
            <x-content.card>
                <form action="{{ route('support.tickets.store') }}" method="POST" class="grid grid-cols-2 gap-4 w-full">
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

                        <article class="text-pretty py-3 px-4 bg-gray-100 rounded-md">
                            {{ $ticket->content }}
                        </article>
                    </div>
                </form>
            </x-content.card>

            <x-content.card>
                <div class="flex flex-col gap-4 w-full">
                    <form action="{{ route('support.tickets.replies.store', $ticket) }}" method="POST"
                          class="grid grid-cols-2 gap-4 w-full">
                        @csrf

                        <div class="col-span-2 flex flex-col">
                            <label for="content">
                                Reply
                            </label>

                            <textarea id="content" name="content" placeholder="Reply to this ticket"
                                      class="rounded-md border border-gray-200"></textarea>
                        </div>

                        <div class="col-span-2">
                            <x-form.primary-button>
                                Post Reply
                            </x-form.primary-button>
                        </div>
                    </form>
                </div>
            </x-content.card>

            <div class="flex flex-col gap-4 w-full">
                <h2 class="text-2xl">Replies</h2>
                @foreach($ticket->replies->reverse() as $reply)
                    <div>
                        <div @class([
                            'rounded bg-white shadow w-full relative flex p-4 text-black',
                            '!bg-blue-600 text-white flex-col items-start' => !$reply->isAuthor(),
                            '!bg-yellow-300 !text-black' => (Auth::user()->hasRole('admin') && !$reply->isAuthor()) && $reply->user->hasRole('admin'),
                        ])>
                            <div class="flex gap-4">
                                <img src="{{ asset($reply->user->profile_photo) }}" alt=""
                                     class="w-10 h-10 rounded-full">
                                <article>
                                    {!! $reply->content !!}
                                </article>
                            </div>
                        </div>

                        <div class="w-full flex justify-end mt-1">
                            <small>Posted by: {{ $reply->user->name }}
                                - {{ $reply->updated_at->diffForHumans() }}</small>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
