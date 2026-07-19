<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Category;
use Illuminate\View\View;
class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    //GET /posts - list all posts
    public function index(Request $request): View
    {
        //hardcoded for now: Module 2 will use databases query
        /*$posts = [
            ['id'=>1,'title'=>'Getting started  with Laravel', 'body'=>'Laravel makes PHP enjoyable...'],
            ['id'=>2,'title'=>'Understanding MVC', 'body'=>'The Model-View-Controller pattern...'],
            ['id'=>3,'title'=>'Blade Templates', 'body'=>'Blade is Laravel\'s templating engine...']
        ];*/

        /*$posts = Post::query()
       
        ->with('author', 'category', 'tags') // eager load — covered in 2.6
        ->published()

        // Dynamic filters — only apply if sent in request
        ->when($request->filled('search'),   fn($q) => $q->search($request->search))
        ->when($request->filled('category'), fn($q) => $q->forCategory($request->category))
        ->when($request->filled('tag'), function ($q) use ($request) {
            $q->whereHas('tags', fn($t) => $t->where('slug', $request->tag));
        })
        ->when($request->filled('author'),   fn($q) => $q->forAuthor($request->author))
        ->when($request->boolean('featured'), fn($q) => $q->featured())

        // Sorting
        ->when($request->filled('sort'), function ($q) use ($request) {
            match ($request->sort) {
                'popular' => $q->popular(),
                'oldest'  => $q->oldest('published_at'),
                default   => $q->latest('published_at'),
            };
        }, fn($q) => $q->latest('published_at'))

        ->paginate(12)
        ->withQueryString();

        $categories = Category::withCount('publishedPosts')
                           ->having('published_posts_count', '>', 0)
                           ->ordered()
                           ->get();

        $popularTags = Tag::withCount('posts')
                      ->orderByDesc('posts_count')
                      ->take(10)
                      ->get();
                      
        */

        $posts = Post::query()
            // ✅ Eager load everything the view needs — 4 extra queries total
            // NOT N queries per post
            ->with([
                'author'   => fn($q) => $q->select('id', 'name', 'avatar'),
                'category' => fn($q) => $q->select('id', 'name', 'slug', 'color'),
                'tags'     => fn($q) => $q->select('id', 'name', 'slug', 'color'),
            ])
            // ✅ Count without loading all comments
            ->withCount('comments')
            // ✅ Boolean check — does it have featured image
            ->withExists('comments as has_comments')
            ->published()
            ->when($request->filled('search'),
                fn($q) => $q->search($request->search))
            ->when($request->filled('category'),
                fn($q) => $q->forCategory($request->category))
            ->when($request->filled('tag'), fn($q) =>
                $q->whereHas('tags', fn($t) =>
                    $t->where('slug', $request->tag)))
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        $categories = Category::query()
                    ->whereHas('publishedPosts')
                    ->withCount('publishedPosts')
                    ->ordered()
                    ->get();
    
        $popularTags = Tag::withCount('posts')
                          ->orderByDesc('posts_count')
                          ->take(10)
                          ->get();
        
        //fake authenticated user
        $user = (object)[
            'name'=>'Web pro',
            'isAdmin' => true,
            'isSubscribed' => false
        ];
        //pass data to the view using compact
        return view('posts.index',compact('posts','user','categories', 'popularTags'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    //GET /posts/{id} - show one post
    /**
     * 4. Update PostController@show to load the post with all needed relationships — author, category, tags, and approvedComments — in one query using with().
     */
    //public function show(string $id)
    public function show(Post $post): View
    {
        //we'll replace this with Post::findOrFail($id) in module 2
        /*$post = [
            'id'=>$id,
            'title'=> 'Sample Post #'.$id,
            'body'=> 'This is the full body of post number '. $id
        ];*/

        //---------------------------------------------------
        /*
        $post = Post::with([
            'author',
            'category',
            'tags',
            'approvedComments'
        ])->findOrFail($id);
        
        */
        //-------------------------------------------------------
        // Load all relationships needed for the show page in one go
        $post->load([
            'author',         // author + their profile
            'category',
            'tags',
            'approvedComments' => fn($q) => $q
                ->with('author')      // nested — comment authors
                ->withCount('replies')
                ->latest(),
        ]);

        // Increment view count atomically
        $post->increment('views_count');

        // Related posts — same category, not this one
        $related = Post::published()
            ->with(['author' => fn($q) => $q->select('id', 'name', 'avatar')])
            ->withCount('comments')
            ->forCategory($post->category_id)
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->take(3)
            ->get();


        return view('posts.show', compact('post', 'related'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
