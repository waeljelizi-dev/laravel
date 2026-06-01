<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','My Blog')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{--this stack collects everything pushed or prepended --}}
    @stack('styles')
    

    <style>
        body{
            font-family: sans-serif;
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
            background: #f0f0f0;
        }
        nav a {
            margin-right: 16px;
            text-decoration: none;
            color: #300ddf;
        }
        /*body{
            font-family: sans-serif;
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
            background: #f0f0f0;
        }

        nav a {
            margin-right: 16px;
            text-decoration: none;
            color: #300ddf;
        }
        
        .card {
            
            background: #5243f3;
            margin: 20px 0;
            padding: 10px;
            box-shadow: 2px 2px 10px rgba(0,0,0,0.3);
            color: #eee;
        }   
        .card h2 {
            color: #251c77;
        }
        .card a {
            text-decoration: none;
            color: #989aff;
        }

        .badge {
            display: block;
            padding: 15px 8px;
            border-radius: 4px;
            color: #fff;
        }

        .badge-info {
            background-color: #3490dc;
        }
        .badge-success{
            background-color: #38c172;
        }*/
        
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <nav>
        <a href="{{route('posts.index')}}">Posts</a>
        <a href="{{route('dashboard.index')}}">Dashboard</a>
        <a href="{{route('about')}}">About</a>
    </nav>
    <hr/>
    <div class="flex gap-6 max-w-6xl mx-auto px-4 py-8">
        {{-- Sidebar - $notification injected automatically by View composer --}}
        <aside class="flex-shrink-0">
            <x-notification-sidebar :user="$user" />
        </aside>
        {{-- yield is a placeholder that child views fill in --}}
        <main class="flex-1">
            @yield('content')   
        </main>
    </div>
    @stack('scripts')
</body>
</html>