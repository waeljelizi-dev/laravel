{{-- $item comes from @each - its one notification object --}}
@php 
    $componentMap = [
        'comment' => 'notifications.comment',
        'like' => 'notifications.like',
        'follow' => 'notifications.follow',
        'systme' => 'notifications.system',
    ];

    //Unknown types fall back to generic - never crashes
    $component = $componentMap[$item->type] ?? 'notifications.generic';

@endphp

<x-dynamic-component
    :component="$component"
    :notification="$item"
/>
