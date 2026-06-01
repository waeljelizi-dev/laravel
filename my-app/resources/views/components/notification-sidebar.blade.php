@props(['notifications'])
@once
    @push('styles')
        <link rel="stylesheet" href="/css/notifications.css">
    @endpush
@endonce
@prepend('styles')
    <link rel="stylesheet" href="/css/notification-rest.css">
@endprepend
<div class="w-80 bg-white rounded-x1 border border-gray-200 shadow-sm overflow-hidden">
    {{-- header --}}
    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
        <h3 class="text-sm font-semibold text-gray-800">Notifications</h3>
        {{-- Badge: only shows when there are unread notifications --}}
        @includeWhen(
            $notifications->where('read', false)->count() > 0,
            'partials.notification-badge',
            ['count' => $notifications->where('read', false)->count()]
        )
    </div>
    {{-- notifications list --}}
    <div class="divide-y divide-gray-50 max-h-96 overflow-y-auto">
        @each(
            'partials.notification-item',
            $notifications,
            'item',
            'partials.no-notifications'
        )
    </div>

    {{-- footer--}}
    @if($notifications->count() > 0) 
        <div class="px-4 py-2 border-t border-gray-100 bg-gray-50">
            <a href="#" class="text-xs text-blue-600 hover:underline font-medium">
                Mark all read
            </a>
        </div>
    @endif
</div>
