@extends('layouts.app')
@section('title',$post->title)
@section('content')
    <a href="{{route('posts.index')}}">Back to posts</a>
    <h1>{{$post->title}}</h1>
    <p>{{$post->body}}</p>
@endsection