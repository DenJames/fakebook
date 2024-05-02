<x-app-layout>
   <grid class="grid grid-cols-12 gap-4">
       <div class="col-span-12 lg:col-span-4">
           <h2 class="text-2xl font-bolt">Open previous chat</h2>

           <div class="flex flex-col gap-y-2 mt-2 max-h-[calc(100vh-200px)] overflow-y-auto">
               @forelse($conversations as $conversation)
                   <a href="{{ route('conversations.show', $conversation->id) }}"
                      class="flex flex-col p-4 bg-white border rounded">
                       <p class="font-bold">
                           {{ $conversation->participant->name }}
                       </p>

                       <div class="flex gap-x-2 items-center">
                           <p class="truncate text-black/50 text-sm w-full">
                               {{ $conversation->messages->last()?->content }}
                           </p>

                           <p class="text-black text-xs text-black/50 mt-[2px] flex justify-end">
                               {{ formatDiffForHumans( $conversation->messages->last()->created_at ?? now()) }}
                           </p>
                       </div>
                   </a>
               @empty
                   <div class="text-center text-2xl">
                       No conversations found.
                   </div>
               @endforelse
           </div>
       </div>

       <div class="col-span-12 lg:col-span-8 ">
           <h2 class="text-2xl font-bolt">Start new chat</h2>
           <div class="flex flex-col gap-y-2 w-full mt-2 max-h-[calc(100vh-200px)] overflow-y-auto">
               @forelse($users as $user)
                   <a href="{{ route('conversations.start', $user) }}"
                      class="flex flex-col p-4 bg-white border rounded">
                       <p class="font-bold">
                            {{ $user->name }}
                       </p>
                   </a>
               @empty
                   <div class="text-center text-2xl">
                      No users found.
                   </div>
               @endforelse
           </div>
       </div>
   </grid>
</x-app-layout>
