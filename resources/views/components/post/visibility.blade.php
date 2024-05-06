@props(['visibility' => 'private', 'width' => 'w-6', 'height' => 'h-6'])

@if($visibility == 'private')
    <x-icons.lock-closed :height="$height" :width="$width"/>
@elseif($visibility == 'friends')
    <x-icons.user-group :height="$height" :width="$width"/>
@elseif($visibility == 'public')
    <x-icons.globe :height="$height" :width="$width"/>
@endif
