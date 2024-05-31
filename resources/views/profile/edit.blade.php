<x-app-layout>
    @push('title', 'Account settings')


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Tabs -->
            <div
                x-data="{
                    selectedId: null,
                    init() {
                        // Set the first available tab on the page on page load.
                        this.$nextTick(() => this.select(this.$id('tab', 1)))
                    },
                    select(id) {
                        this.selectedId = id
                    },
                    isSelected(id) {
                        return this.selectedId === id
                    },
                    whichChild(el, parent) {
                        return Array.from(parent.children).indexOf(el) + 1
                    }
                }"
                x-id="['tab']"
            >
                <!-- Tab List -->
                <ul
                    x-ref="tablist"
                    @keydown.right.prevent.stop="$focus.wrap().next()"
                    @keydown.home.prevent.stop="$focus.first()"
                    @keydown.page-up.prevent.stop="$focus.first()"
                    @keydown.left.prevent.stop="$focus.wrap().prev()"
                    @keydown.end.prevent.stop="$focus.last()"
                    @keydown.page-down.prevent.stop="$focus.last()"
                    role="tablist"
                    class="-mb-px flex items-stretch"
                >
                    <!-- Tab -->
                    <li>
                        <button
                            :id="$id('tab', whichChild($el.parentElement, $refs.tablist))"
                            @click="select($el.id)"
                            @mousedown.prevent
                            @focus="select($el.id)"
                            type="button"
                            :tabindex="isSelected($el.id) ? 0 : -1"
                            :aria-selected="isSelected($el.id)"
                            :class="isSelected($el.id) ? 'border-gray-200 bg-white' : 'border-transparent'"
                            class="inline-flex rounded-t-md border-t border-l border-r px-5 py-2.5"
                            role="tab"
                        >User settings
                        </button>
                    </li>

                    <li>
                        <button
                            :id="$id('tab', whichChild($el.parentElement, $refs.tablist))"
                            @click="select($el.id)"
                            @mousedown.prevent
                            @focus="select($el.id)"
                            type="button"
                            :tabindex="isSelected($el.id) ? 0 : -1"
                            :aria-selected="isSelected($el.id)"
                            :class="isSelected($el.id) ? 'border-gray-200 bg-white' : 'border-transparent'"
                            class="inline-flex rounded-t-md border-t border-l border-r px-5 py-2.5"
                            role="tab"
                        >Privacy settings
                        </button>
                    </li>
                </ul>

                <!-- Panels -->
                <div role="tabpanels" class="rounded-b-md border border-gray-200 bg-white">
                    <!-- Panel -->
                    <section
                        x-show="isSelected($id('tab', whichChild($el, $el.parentElement)))"
                        :aria-labelledby="$id('tab', whichChild($el, $el.parentElement))"
                        role="tabpanel"
                    >
                        <x-content.card>
                            <div class="max-w-xl">
                                @include('profile.partials.update-profile-picture-form')
                            </div>
                        </x-content.card>

                        <x-content.card>
                            <div class="max-w-xl">
                                @include('profile.partials.update-profile-information-form')
                            </div>
                        </x-content.card>

                        <x-content.card>
                            <div class="max-w-xl">
                                @include('profile.partials.update-password-form')
                            </div>
                        </x-content.card>

                        <x-content.card>
                            <div class="max-w-xl">
                                @include('profile.partials.sessions-form')
                            </div>
                        </x-content.card>

                        <x-content.card>
                            <div class="max-w-xl">
                                @include('profile.partials.delete-user-form')
                            </div>
                        </x-content.card>
                    </section>

                    <section
                        x-show="isSelected($id('tab', whichChild($el, $el.parentElement)))"
                        :aria-labelledby="$id('tab', whichChild($el, $el.parentElement))"
                        role="tabpanel"
                        class="p-4"
                    >


                        <form action="{{ route('profile.private-settings.update', $user) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="flex items-center justify-between gap-2">
                                <label for="visibility_type" class="w-full">
                                    Profile visibility
                                </label>

                                <select name="visibility_type" id="visibility_type" class="rounded">
                                    @foreach (App\Enums\ProfileVisibilityTypes::cases() as $type)
                                        <option value="{{ $type->value }}" {{ $privacySettings->visibility_type->value === $type->value ? 'selected' : '' }}>
                                            {{ $type->toUpperSnakeCase() }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            @foreach(App\Enums\PrivacySettings::cases() as $setting)
                                <div
                                    x-data="{ value: {{ $privacySettings->{$setting->value} ? 'true' : 'false' }} }"
                                    x-id="['toggle-label']"
                                    class="flex items-center justify-between gap-2"
                                >
                                    <div>
                                        <input type="hidden" name="{{ $setting->value }}" :value="value">

                                        <!-- Label -->
                                        <label
                                            @click="$refs.toggle.click(); $refs.toggle.focus()"
                                            :id="$id('toggle-label')"
                                            class="text-gray-900 font-medium cursor-pointer"
                                        >
                                            {{ $setting->toUpperSnakeCase() }}
                                        </label>
                                    </div>

                                    <!-- Button -->
                                    <button
                                        x-ref="toggle"
                                        @click="value = ! value"
                                        type="button"
                                        role="switch"
                                        :aria-checked="value"
                                        :aria-labelledby="$id('toggle-label')"
                                        :class="value ? 'bg-blue-500' : 'bg-slate-300'"
                                        class="relative ml-4 inline-flex w-14 rounded-full py-1 transition mt-2"
                                    >
                                        <span
                                            :class="value ? 'translate-x-8' : 'translate-x-1'"
                                            class="bg-white h-5 w-5 rounded-full transition shadow-md"
                                            aria-hidden="true"
                                        ></span>
                                    </button>
                                </div>
                            @endforeach

                            <x-form.primary-button type="submit">
                                {{ __('Update') }}
                            </x-form.primary-button>
                        </form>


                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
