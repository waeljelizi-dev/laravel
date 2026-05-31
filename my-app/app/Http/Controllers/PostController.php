<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    //GET /posts - list all posts
    public function index()
    {
        //hardcoded for now: Module 2 will use databases query
        $posts = [
            ['id'=>1,'title'=>'Getting started  with Laravel', 'body'=>'Laravel makes PHP enjoyable...'],
            ['id'=>2,'title'=>'Understanding MVC', 'body'=>'The Model-View-Controller pattern...'],
            ['id'=>3,'title'=>'Blade Templates', 'body'=>'Blade is Laravel\'s templating engine...']
        ];
          //fake authenticated user
        $user = (object)[
            'name'=>'Web pro',
            'isAdmin' => true,
            'isSubscribed' => false
        ];
        //pass data to the view using compact
        return view('posts.index',compact('posts','user'));
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
    public function show(string $id)
    {
        //we'll replace this with Post::findOrFail($id) in module 2
        $post = [
            'id'=>$id,
            'title'=> 'Sample Post #'.$id,
            'body'=> 'This is the full body of post number '. $id
        ];
      

        return view('posts.show', compact('post'));
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
