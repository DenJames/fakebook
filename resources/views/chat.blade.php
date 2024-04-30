<x-app-layout>
    <div class="relative rounded bg-gray-100 p-4 w-full h-[1200px] space-y-6">
        <x-chat.sender/>

        {{-- Person u chatting with --}}
        <div class="flex flex-col items-end gap-y-4 w-full">
            @for($i = 0; $i < 5; $i++)
                <x-chat.receiver/>
            @endfor
        </div>

        <div class="w-full px-4 flex flex-col absolute bottom-5">
            <div class="w-[97%]">

                <input class="w-full  p-2 rounded h-24 border-0" type="text" placeholder="Type a message...">

                <button class="rounded bg-blue-500 text-blue-100 py-2 px-4 w-full text-center mt-4 hover:bg-blue-600 transition-all duration-300">
                    Send message
                </button>
            </div>

        </div>
    </div>
</x-app-layout>
