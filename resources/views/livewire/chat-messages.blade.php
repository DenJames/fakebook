<div>
    @foreach($messages as $message)
        @if($message->userIsAuthor())
            <livewire:message-sender :message="$message"/>
        @else
            <livewire:message-receiver :message="$message"/>
        @endif
    @endforeach
</div>
