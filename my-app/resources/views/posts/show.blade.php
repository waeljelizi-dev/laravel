@extends('layouts.app')
@section('title',$post_specific['title'])
@section('content')
    <a href="{{route('posts.index')}}">Back to posts</a>
    <h1>{{$post_specific['title']}}</h1>
    <p>{{$post_specific['body']}}</p>
@endsection