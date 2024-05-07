import './bootstrap';

import Alpine from 'alpinejs';
import { Editor } from '@tiptap/core'
import StarterKit from '@tiptap/starter-kit'
import {Placeholder} from "@tiptap/extension-placeholder";
import Paragraph from '@tiptap/extension-paragraph'
import Text from '@tiptap/extension-text'
import Heading from '@tiptap/extension-heading'
import { Modal } from 'flowbite';

// prose dark:prose-invert prose-sm sm:prose-base lg:prose-lg xl:prose-2xl m-5 focus:outline-none prose-h1:h1 prose-h1:text-2xl

let placeholder = document.getElementById('timeline-textarea-status').getAttribute('x-data-placeholder');
document.addEventListener('alpine:init', () => {
    Alpine.data('editor', (content) => {
        let editor // Alpine's reactive engine automatically wraps component properties in proxy objects. Attempting to use a proxied editor instance to apply a transaction will cause a "Range Error: Applying a mismatched transaction", so be sure to unwrap it using Alpine.raw(), or simply avoid storing your editor as a component property, as shown in this example.
        return {
            updatedAt: Date.now(), // force Alpine to rerender on selection change
            init() {
                const _this = this

                editor = new Editor({
                    element: this.$refs.element,
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
                    content: content,
                    onCreate({ editor }) {
                        _this.updatedAt = Date.now()
                    },
                    onUpdate({ editor }) {
                        _this.updatedAt = Date.now()
                    },
                    onSelectionUpdate({ editor }) {
                        _this.updatedAt = Date.now()
                    }
                });

                // Get the editor content as plain text
                function getPlainText() {
                    const plainText = editor.getHTML() // Get content as HTML
                        .replace(/<\/?[^>]+(>|$)/g, "").replace('\n', ' ') // Strip HTML tags
                    return plainText
                }

                // Update the input element with the plain text content
                function updateInput() {
                    if (getPlainText() !== '' ) {
                        const input = document.getElementById('timeline_status_input')
                        input.value = getPlainText()
                    }
                }

                // Update the input when the editor content changes
                editor.on('update', () => {
                    updateInput()
                })

                // Initial update
                updateInput()

            },
            isLoaded() {
                return editor
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
    })
});


window.Alpine = Alpine;

Alpine.start();

// set the modal menu element
const $targetEl = document.getElementById('default-modal');

// options with default values
const options = {
    placement: 'bottom-right',
    backdrop: 'dynamic',
    backdropClasses:
        'bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40',
    closable: true,
    onHide: () => {
        console.log('modal is hidden');
    },
    onShow: () => {
        console.log('modal is shown');
    },
    onToggle: () => {
        console.log('modal has been toggled');
    },
};

const modal = new Modal($targetEl, options);
