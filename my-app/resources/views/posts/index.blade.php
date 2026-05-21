@extends('layouts.app')
@section('title','All Posts')
@section('content')
    <h1>All Posts</h1>
    @forelse ($posts as $post)
        <div class="card">
            <h2>{{$post['title']}}</h2>
            <p>{{Str::limit($post['body'], 100)}}</p>
            <a href="{{route('posts.show', $post['id'])}}">Read More -> </a>
        </div>
    @empty
        <p>No posts yet!</p>
    @endforelse
@endsection