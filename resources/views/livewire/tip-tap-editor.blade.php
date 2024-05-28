<div x-data="setupEditor('')"
     x-init="() => init($refs.editor)"
     wire:ignore class="text-white">
        <div class="menu flex items-center gap-2">
            <button @click="toggleHeading({ level: 1 })"
                    :class="{ 'is-active': isActive('heading', { level: 1 }, updatedAt) }" type="button">
                <div class="text-white">
                    <x-icons.h1 width="w-[15px]"/>
                </div>
            </button>
            <button @click="toggleHeading({ level: 2 })"
                    :class="{ 'is-active': isActive('heading', { level: 2 }, updatedAt) }" type="button">
                <div class="text-white">
                    <x-icons.h2 width="w-[15px]"/>
                </div>
            </button>
            <button @click="toggleHeading({ level: 3 })"
                    :class="{ 'is-active': isActive('heading', { level: 3 }, updatedAt) }" type="button">
                <div class="text-white">
                    <x-icons.h3 width="w-[15px]"/>
                </div>
            </button>
            <button @click="toggleHeading({ level: 4 })"
                    :class="{ 'is-active': isActive('heading', { level: 4 }, updatedAt) }" type="button">
                <div class="text-white">
                    <x-icons.h4 width="w-[15px]"/>
                </div>
            </button>
            <button @click="toggleHeading({ level: 5 })"
                    :class="{ 'is-active': isActive('heading', { level: 5 }, updatedAt) }" type="button">
                <div class="text-white">
                    <x-icons.h5 width="w-[15px]"/>
                </div>
            </button>
            <button @click="toggleHeading({ level: 6 })"
                    :class="{ 'is-active': isActive('heading', { level: 6 }, updatedAt) }" type="button">
                <div class="text-white">
                    <x-icons.h6 width="w-[15px]"/>
                </div>
            </button>
            <button
                @click="toggleBold()"
                :class="{ 'is-active' : isActive('bold', updatedAt) }"
                type="button"
            >
                <x-icons.bold width="w-3" height="h-3"/>
            </button>
            <button
                @click="toggleItalic()"
                :class="{ 'is-active' : isActive('italic', updatedAt) }"
                type="button"
            >
                <x-icons.italic width="w-3" height="h-3"/>
            </button>
        </div>

    <div x-ref="editor"
         id="{{ $editorId }}" class="pt-1 text-white"  wire:model="content"></div>

    @if($post)
        <div class="innterHTML" id="editor-text">{!! str_replace('<br class="ProseMirror-trailingBreak">', '', $post->content) !!}</div>
    @endif
</div>


