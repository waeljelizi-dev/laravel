@props(['notification'])
<div class="flex items-center gap-3 px-4 py-3
    {{$notification->read ? 'bg-white' : 'bg-green-50'}} 
    hover:bg-gray-50 transition-colors">
    <span class="text-xl">👥</span>
    <div class="flex-1">
        <p class="text-sm font-medium text-gray-900">{{$notification->title}}</p>
        <p class="text-xs text-gray-400 mt-0.5">{{$notification->time}}</p>
    </diV>
    {{-- follow notifications get an action button --}}
    <button class="text-xs bg-blue-600 text-white px-3 py-1 rounded-full font-medium hover:bg-blue-700 transition-color flex-shrink-0">
        Follow back
    </button>
</div>