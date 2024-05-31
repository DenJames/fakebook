<x-app-layout>
    @push('title', 'Create ticket')
    <x-content.card>
        <form action="{{ route('support.tickets.store') }}" method="POST" class="grid grid-cols-2 gap-4 w-full">
            @csrf

            <div class="flex flex-col">
                <label for="category">
                    Category
                </label>

                <select name="ticket_category_id" id="category" class="rounded-md border border-gray-200">
                    <option value="null">
                        Select a category
                    </option>

                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col">
                <label for="subject">
                    Subject
                </label>

                <input id="subject" name="subject" placeholder="Specify a subject" class="rounded-md border border-gray-200" />
            </div>

            <div class="col-span-2 flex flex-col">
                <label for="description">
                    Description
                </label>

                <textarea id="description" name="content" placeholder="Describe your issue" class="rounded-md border border-gray-200"></textarea>
            </div>

            <div class="col-span-2">
                <x-form.primary-button>
                    Create ticket
                </x-form.primary-button>
            </div>
        </form>
    </x-content.card>
</x-app-layout>
