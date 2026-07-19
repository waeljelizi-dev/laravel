@props(['notification'])
<div class="flex items-start gap-3 px-4 py-3
    {{$notification->read ? 'bg-white' : 'bg-blue-50'}} 
    hover:bg-gray-50 transition-colors">
    <span class="text-xl mt-0.5">💬</span>
    <div class="flex-1 min-w-0">
        <p class="text-sm font-medium text-gray-900 truncate">{{$notification->title}}</p>
        {{-- body is optional only render if present --}}
        @if($notification->body)
            <p class="text-xs text-gray-500 mt-0.5 lline-clamp-2">
                {{$notification->body}}
            </p>
        @endif
        <p class="text-xs text-gray-400 mt-1">{{$notification->time}}</p>
    </diV>
    {{-- unread dot--}}
    @unless($notification->read)
        <span class="w-2 h-2 bg-blue-500 rounded-full mt-1.5 flex-shrink-0"></span>
    @endunless
</div>