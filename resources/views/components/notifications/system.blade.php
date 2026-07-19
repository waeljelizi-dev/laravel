@props(['notification'])
<div class="flex items-start gap-3 px-4 py-3
    {{$notification->read ? 'bg-white' : 'bg-yellow-50'}} 
    hover:bg-gray-50 transition-colors">
    <span class="text-xl mt-0.5">⚙️</span>
    <div class="flex-1">
        <p class="text-sm font-medium text-gray-900">{{$notification->title}}</p>
        @if($notification->body)
            <p class="text-xs text-gray-500 mt-0.5">{{$notification->time}}</p>
        @endif
    </diV>
</div>