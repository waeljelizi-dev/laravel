<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //hardcode data 
        $posts = [
            ['id'=>1, 'title'=> 'Getting Started with Laravel','body'=>'Laravel makes PHP enjoyable...'],
            ['id'=>2, 'title'=>'Understanding MVC','body'=>'The Model-View-Controller pattern...'],
            ['id'=>3, 'title'=>'Blade Templates','body'=>'Blade is Laravel\'s templating engine...']
        ];

        //pass data to view using compact function
        return view('posts.index', compact('posts'));
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
     * GET /posts/{id} - Show a specific post
     */
    public function show(string $id)
    {   
        //we'll replace this function by Post::findOrFail($id) once we have a model and database
        $post_specific = [
            'id' => $id,
            'title' => "Sample Post $id",
            'body' => "This is the body of post $id. It contains detailed information"
        ];

        //this will pass the $post_specific variable to the view
        return view('posts.show',compact('post_specific'));
    
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
