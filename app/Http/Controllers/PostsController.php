<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Post;
use DB;

class PostsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $posts = Post::all();
        $posts = Post::orderBy('title', 'asc')->get();
        // $posts = DB::select('SELECT * FROM posts');
        return view('posts.index')->with('posts', $posts);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'title' => 'required',
            'body' => 'required',
            'cover_image' => 'image|nullable|max:1999'
        ]);

        // Handle the file uploaded
        if($request->hasFile('cover_image')){
            // get file name with extension
            $filenameWithExt = $request->file('cover_image')->getClientOriginalExtension();
            //get just file name
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            
            // get just ext
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            //filename to store
            $fileNameToStore = $filename . '_'  . time() . '.' . $extension;

            //upload image
            $path = $request->file('cover_image')->storeAs('/public/cover_images', $fileNameToStore);
        }else{
            $fileNameToStore = 'noimage.jpg';
        }
        
        $post = new Post;
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->user_id = auth()->user()->id;
        $post->cover_image = $fileNameToStore;
        $post->save();

        return redirect('/posts')->with('success', 'Post Created');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::find($id);
        return view('posts.show')->with('post', $post);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $post = Post::find($id);

        if(auth()->user()->id !== $post->user_id){
            return redirect('posts')->with('error', 'Access Denied');
        }

        return view('posts.edit')->with('post', $post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request,[
            'title' => 'required',
            'body' => 'required',
            'cover_image' => 'image|nullable|max:1999'
        ]);

        // Handle the file uploaded
        if($request->hasFile('cover_image')){
            // get file name with extension
            $filenameWithExt = $request->file('cover_image')->getClientOriginalExtension();
            //get just file name
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            
            // get just ext
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            //filename to store
            $fileNameToStore = $filename . '_'  . time() . '.' . $extension;

            //upload image
            $path = $request->file('cover_image')->storeAs('/public/cover_images', $fileNameToStore);
        }
        
        $post = Post::find($id);
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        if($request->hasFile('cover_image')){
            $post->cover_image = $fileNameToStore;
        }
        $post->save();

        return redirect('/posts')->with('success', 'Post Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::find($id);

        if(auth()->user()->id !== $post->user_id){
            return redirect('posts')->with('error', 'Access Denied');
        }

        if($post->cover_image != 'noimage.jpg'){
            Storage::delete('/public/cover_images/' . $post->cover_image);
        }

        $post->delete();
        return redirect('/posts')->with('success', 'Post removed');
    }
}
