@extends('layouts.app')
@section('title',$post['title'])
{{-- PREPEND : this lands at position #1 in the styles stack--}}
{{-- Critical: must load before any component tried to use it--}}
@prepend('styles')
    <link rel="stylesheet" href="/css/syntax-highlighter.css">
@endprepend
@section('content')
    <a href="{{route('posts.index')}}">Retour to all posts</a>
    <h1>{{$post['title']}}</h1>
    <x-code-block class="text" :code="$post['body']"/> {{-- this component also pushes a style --}}
    <x-button variant="danger" size="lg" type="button">Delete post</x-button>
    <x-button variant="primary" size="md" type="button">Add Post</x-button>
    <x-button variant="secondary" size="sm" type="button">Edit Post</x-button>
    <x-button variant="ghost" size="lg" href="http://localhost:8000/posts">Return</x-button>
    <br/>
    <x-alert type="success">
        Your post was published successfully.
    </x-alert>
    
    {{-- With a named title slode --}}
    <x-alert type="danger" :dismissible="true">
        <x-slot:title>Payment Failed</x-slot:title>
        Your cart was declined. Please update your mayment method.
    </x-alert>
    <x-alert type="warning">
    You have 3 days left on your free trial.
    </x-alert>
    <br/>
    {{-- Full card with all three slots --}}
    <x-card>
        <x-slot:header>
            Latest Posts
        </x-slot:header>
        <p>Here is the main content of the card</p>
        <p>It can be anything - text , tables, forms... </p>
        <x-slot:footer>
            Last updated 5 minutes ago
            <x-button size="sm" variant="ghost" class="ml-auto">Update</x-button>
        </x-slot:footer>
    </x-card>
    {{-- card without header or footer --}}
    <x-card>
        <img src="/post-thumbnail.jpg" alt="post-thumbnail"/>
    </x-card>

    <br/>
    {{-- All standard html attributes pass through $attributes variable --}}
    <x-input
        label="Email Address"
        name="email"
        type="email"
        placeholder="you@example.com"
        :error="$errors->first('email')"
        required
        autocomplete="email"
    />

    
@endsection