<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title','My Blog')</title>
    <style>
        body{
            font-family: sans-serif;
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }   
        nav a{
            margin-right: 16px;
            text-decoration: none;
            color: #E24B4A;
        }
        .card {
            border: 1px solid #EEE;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 16px;
        }
    </style>
</head>
<body>
    <nav>
        <a href="{{route('posts.index')}}">Blog</a>
        <a href="{{route('about')}}">About</a>
    </nav>
    <hr/>
    {{-- @yield is the placeholder that a child will fill in --}}
    @yield('content')
</body>
</html>