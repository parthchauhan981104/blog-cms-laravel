<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Category;
use App\Tag;
use App\Http\Requests\Posts\CreatePostsRequest;
use App\Http\Requests\Posts\UpdatePostRequest;


class PostsController extends Controller
{
    
    public function __construct()
    {
       $this->middleware('VerifyCategoriesCount')->only(['create', 'store']); //apply middleware only on these routes
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('posts.index')->with('posts', Post::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create')->with('categories', Category::all())->with('tags', Tag::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePostsRequest $request)
    {
        //store image in storage->app->public folder with a unique hash name(returned back)
        //php artisan storage:link links root->public folder with this protected storage->app->public folder
        
        $image = $request->image->store('posts');

        $post = Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'content' => $request->content,
            'image' => $image,
            'published_at' => $request->published_at,
            'category_id' => $request->category_id
        ]);

        if($request->tags){
            $post->tags()->attach($request->tags);
        }

        session()->flash('success', 'Post created successfully.');

        return redirect(route('posts.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        return view('posts.create')->with('post', $post)->with('categories', Category::all())->with('tags', Tag::all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        //only store these for security purposes
        $data = $request->only(['title', 'description', 'published_at', 'content', 'category_id']);

        if($request->hasFile('image')){

            $image = $request->image->store('posts');
            $post->deleteImage; //used method in Post model

            $data['image'] = $image;

        }

        if($request->tags){
            $post->tags()->sync($request->tags);
        }

        $post->update($data);

        session()->flash('success', 'Post updated successfully.');


        return redirect(route('posts.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) //cant use route model binding here as trashed posts cant be found this way
    {
        
        $post = Post::withTrashed()->where('id', $id)->firstOrFail();

        if($post->trashed()) {
            //permanently delete and delete image too
            $post->deleteImage;
            $post->forceDelete();
            session()->flash('success', 'Post deleted successfully.');
        } else{
            //soft delete
            $post->delete();
            session()->flash('success', 'Post trashed successfully.');
        }


        return redirect(route('posts.index'));
    }

    /**
     * Display a list of trashed posts
     *
     * @return \Illuminate\Http\Response
     */
    public function trashed()
    {
        $trashed = Post::onlyTrashed()->get();
        return view('posts.index')->with('posts', $trashed);
    }

    /**
     * restore a trashed post
     *
     * @return \Illuminate\Http\Response
     */
    public function restorePost($id)
    {
        $post = Post::withTrashed()->where('id', $id)->firstOrFail();
        $post->restore();
        session()->flash('success', 'Post restored successfully.');
        return redirect()->back();
    }
}
