<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Post;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Tag;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    protected $validation = [
        'date' => 'required|date',
        'content' => 'required|string',
        'image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048'
    ];

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
        $tags = Tag::all();

        return view('admin.posts.create', compact('tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validation = $this->validation;
        $validation['title'] = 'required|string|max:255|unique:posts';

        // validation
        $request->validate($this->validation);

        $data = $request->all();

        $data['published'] = !isset($data['published']) ? 0 : 1;

        // upload file image

        if(isset($data['image'])){

            $data['image'] = Storage::disk('public')->put('images', $data['image']);
        }


        // imposto lo slug partendo dal title
        
        $data['slug'] = Str::slug($data['title'], '-');


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

        $newPost = Post::create($data);

        // aggiungo i tags
        $newPost->tags()->attach($data['tags']);


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
    public function edit(Post $post)
    {
        $tags = Tag::all();
        
        return view('admin.posts.edit', compact('post', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $validation = $this->validation;
        $validation['title'] = 'required|string|max:255|unique:posts,title,' . $post->id;

        // validation
        $request->validate($validation);

        $data = $request->all();

        // controllo checkbox
        $data['published'] = !isset($data['published']) ? 0 : 1;
        // imposto lo slug partendo dal title
        $data['slug'] = Str::slug($data['title'], '-');

        // Update
        $post->update($data);

        // aggiorno i tags
        $post->tags()->sync($data['tags']);

        // return
        return redirect()->route('admin.posts.show', $post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->tags()->detach();

        $post -> delete();

        return redirect()->route('admin.posts.index')->with('message', 'il post ?? stato cancellato!');
    }
}
