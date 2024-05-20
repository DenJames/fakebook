<div>
    @foreach($messages as $message)
        @if($message->userIsAuthor())
            <x-chat.sender :message="$message"/>
        @else
            <x-chat.receiver :message="$message"/>
        @endif
    @endforeach
</div>
