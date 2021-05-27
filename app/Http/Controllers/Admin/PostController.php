<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Post;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class PostController extends Controller
{
    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();

        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validation

        $data = $request->all();

        if( !isset ($data['published']) ) {

            $data['published'] = false;

        } else {

            $data['published'] = true;

        }

        // imposto lo slug partendo dal title
        
        $data['slug'] = Str::slug($data['title'], '-');

        $request->validate([
            'title' => 'required|max:255',
            'date' => 'required|date',
            'content' => 'required|string',
            'image' => 'nullable|url'
        ]);

        // insert

        // $newPost = new Post();

        // $newPost->title = $data['title'];

        // $newPost->date = $data['date'];

        // $newPost->content = $data['content'];

        // $newPost->image = $data['image'];

        // $newPost->slug = Str::slug($data['title'], '-');

        // $newPost->published = $data['published'];

        // $newPost->save();

        // redirect

        Post::create($data);

        return redirect()->route('admin.posts.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        
    }
}
