@once
    @push('scripts')
        <script>
            console.log('Loading once');
        </script>
    @endpush
@endonce
<div class="card">
    <h2>{{$post['title']}}</h2>
    <p>{{Str::limit($post['body'],50)}}</p>
    <a href="{{ route('posts.show', $post) }}">
        Read more
    </a>
</div>