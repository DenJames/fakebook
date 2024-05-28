import './bootstrap';

import Alpine from 'alpinejs';
import {Editor} from '@tiptap/core'
import StarterKit from '@tiptap/starter-kit'
import {Placeholder} from "@tiptap/extension-placeholder";
import Paragraph from '@tiptap/extension-paragraph'
import Text from '@tiptap/extension-text'
import Heading from '@tiptap/extension-heading'
import {Modal} from 'flowbite';
import Swal from 'sweetalert2'

// prose dark:prose-invert prose-sm sm:prose-base lg:prose-lg xl:prose-2xl m-5 focus:outline-none prose-h1:h1 prose-h1:text-2xl

let placeholder = document.getElementById('timeline_status_input')?.getAttribute('placeholder');

window.setupEditor = function (content) {
    let editor

    return {
        content: content,
        updatedAt: Date.now(), // force Alpine to rerender on selection change

        init(element) {
            if (document.getElementById('editor-text')) {
                content = document.getElementById('editor-text').innerHTML
                document.getElementById('editor-text').remove()
            }


            editor = new Editor({
                element: element,
                extensions: [
                    StarterKit,
                    Placeholder.configure({
                        placeholder: placeholder,
                    }),
                    Heading.configure({
                        levels: [1, 2, 3, 4, 5, 6],
                    }),
                    Paragraph,
                    Text,
                ],
                editorProps: {
                    attributes: {
                        class: 'prose dark:prose-invert prose-sm sm:prose-base lg:prose-lg xl:prose-2xl m-5 focus:outline-none prose-h1:text-2xl',
                    },
                },
                content: document.getElementById('editor-text') ? document.getElementById('editor-text').innerHTML : content,
                onCreate({editor}) {
                    this.updatedAt = Date.now()
                },
                onSelectionUpdate({editor}) {
                    this.updatedAt = Date.now()
                },
                onUpdate: ({editor}) => {
                    this.content = editor.getHTML()
                    this.updatedAt = Date.now()
                },
            });

            function getPlainText() {
                const plainText = editor.getHTML() // Get content as HTML
                    .replace(/<\/?[^>]+(>|$)/g, "").replace('\n', ' ') // Strip HTML tags
                return plainText
            }

            // Update the input when the editor content changes
            editor.on('update', () => {
                updateInput()
            });

            // Initial update
            updateInput();

            function updateInput() {
                if (getPlainText() !== '' ) {
                    const input = document.getElementById('timeline_status_input')
                    input.value = getPlainText()
                }
            }

            this.$watch('content', (content) => {
                // If the new content matches TipTap's then we just skip.
                if (content === editor.getHTML()) return

                /*
                  Otherwise, it means that a force external to TipTap
                  is modifying the data on this Alpine component,
                  which could be Livewire itself.
                  In this case, we just need to update TipTap's
                  content and we're good to do.
                  For more information on the `setContent()` method, see:
                    https://www.tiptap.dev/api/commands/set-content
                */
                editor.commands.setContent(content, false)
            })
        },
        isActive(type, opts = {}) {
            return editor.isActive(type, opts)
        },
        toggleHeading(opts) {
            editor.chain().toggleHeading(opts).focus().run()
        },
        toggleBold() {
            editor.chain().toggleBold().focus().run()
        },
        toggleItalic() {
            editor.chain().toggleItalic().focus().run()
        },
    }
}

const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    }
});

// Global variables
window.Alpine = Alpine;
window.Swal = Swal;
window.Toast = Toast;

