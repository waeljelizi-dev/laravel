@props(['code'])
{{--PUSH: appends after the syntax-highlighter.css --}}
{{-- Safe because syntax-highligher.css is already guaranteeded to be first --}}
@push('styles')
    <link rel="stylesheet" href="/css/code-theme.css">
@endpush

<pre><code>{{$code}}</code></pre>