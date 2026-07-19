{{-- Fallback for any unknow notification type --}}
@props(['notification'])
<div class="flex items-start gap-3 px-4 py-3 bg-white hover:bg-gray-50 transition-colors">
    <span class="text-xl mt-0.5">🔔</span>
    <div class="flex-1">
        <p class="text-sm text-gray-800">{{$notification->title}}</p>
        @if($notification->body)
            <p class="text-xs text-gray-500 mt-0.5">{{$notification->body}}</p>
        @endif
        <p class="text-xs text-gray-400 mt-1">{{$notification->time}}</p>
    </div>
</div>