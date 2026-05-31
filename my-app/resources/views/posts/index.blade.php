@extends('layouts.app')
@section('title','All Posts')
@prepend('styles')
    <link rel="stylesheet" href="/css/posts-base.css">
@endprepend
@section('content')
    <x-notification-sidebar :user="$user"/>
    <h1>All Posts</h1>

    {{--includeWhen--}}
    @includeWhen($user->isAdmin, 'partials.admin-bar')

    {{-- includeUnless--}}
    @includeUnless($user->isSubscribed, 'partials.subscribe-banner')

    {{-- includeFirst --}}
    @includeFirst([
        'partials.special-hero',
        'partials.default-hero'
    ])

    <x-badge type="success">All posts were loaded</x-badge>
    <div class="posts-grid">
        {{-- each--}}
        @each(
            'partials.post-card',
            $posts,
            'post',
            'partials.no-posts'
        )
    </div>
@endsection