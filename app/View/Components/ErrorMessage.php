<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ErrorMessage extends Component
{
    public $message;

    /**
     * Create a new component instance.
     *
     * @return void
     * @throws \JsonException
     */
    public function __construct()
    {
        $errors = session('errors');

        if ($errors) {
            // Assuming $errors is an instance of MessageBag
            if ($errors->any()) {
                // Grabbing the first error message regardless of the field
                $this->message = $errors->all()[0];
            }
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.error-message', ['message' => $this->message]);
    }
}
