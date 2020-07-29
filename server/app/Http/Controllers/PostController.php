<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $posts = Post::with('user')->get();
        return response()->json($posts);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'body'=> 'required',
        ]);
        $inputs = $request->toArray();
        $inputs['user_id'] = auth()->user()->id;
        $post = Post::create($inputs);
        return response()->json(['message'=> 'post created',
        'post' => $post]);
    }

    public function show()
    {
        $post = Post::where('user_id', auth()->user()->id)->get();
        return $post;
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'body' => 'required',
            'user_id' => 'required'
        ]);
        $post = Post::where('id', '=', $id)->first();
       $post->update($request->all());
        return response()->json([
            'message' => 'post updated!',
            'post' => $post
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $post = Post::where('id', '=', $id)->where('user_id', auth()->user()->id)->first();
        $post->delete();
        $remainPost = Post::where('user_id', auth()->user()->id)->get();
        return response()->json([
            'message' => 'post deleted',
            'remain-Post' => $remainPost
        ]);

    }
}
