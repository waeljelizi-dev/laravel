@props(['notification'])
<div class="flex items-center gap-3 px-4 py-3
    {{$notification->read ? 'bg-white' : 'bg-red-50'}} 
    hover:bg-gray-50 transition-colors">
    <span class="text-xl mt-0.5">❤️</span>
    <div class="flex-1">
        <p class="text-sm font-medium text-gray-900">{{$notification->title}}</p>
        <p class="text-xs text-gray-400 mt-0.5">{{$notification->time}}</p>
    </diV>
    @unless($notification->read)
        <span class="w-2 h-2 bg-blue-500 rounded-full mt-1.5 flex-shrink-0"></span>
    @endunless
</div>