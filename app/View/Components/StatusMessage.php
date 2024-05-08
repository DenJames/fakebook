<?php

namespace App\View\Components;

use Illuminate\View\Component;

class StatusMessage extends Component
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
        $status = session('message');

        if (is_string($status)) {
            try {
                $statusArray = json_decode($status, true, 512, JSON_THROW_ON_ERROR);
                $this->message = $statusArray['message'] ?? null;
            } catch (\JsonException $e) {
                $this->message = $status;
            }
        } else {
            $this->message = $status;
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.status-message', ['message' => $this->message]);
    }
}
