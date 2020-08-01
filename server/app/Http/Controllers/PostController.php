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
            'user_id' => 'required'
        ]);
        $inputs = $request->toArray();
        if($inputs['user_id'] != auth()->user()->id){
            return response()->json(['message'=>'wrong user']);
        }
        $inputs['user_id'] = auth()->user()->id;
        $post = Post::create($inputs);
        $finalPost = Post::where('id', $post->id)->with('user')->get();
        return response()->json($finalPost);
    }

    public function show()
    {
        $post = Post::where('user_id', auth()->user()->id)->get();
        return $post;
    }

    public function showById($id)
    {
        $post = Post::where('id', $id)->with('user')->get();
        return $post;
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'body' => 'required',
            'user_id' => 'required'
        ]);
        $post = Post::where('id', '=', $id)->where('user_id', auth()->user()->id)->first();

        $inputs = $request->toArray();
        if($inputs['user_id'] != auth()->user()->id){
            return response()->json(['message'=>'wrong user']);
        }
       $post->update($inputs);
        return response()->json([
            'message' => 'post updated!',
            'post' => $post
        ]);
    }

    public function destroy(Request $request, $id)
    {
        try{
        $post = Post::where('id', '=', $id)->where('user_id', auth()->user()->id)->first();
        if($post!=null){

            $post->delete();

            $remainPost = Post::where('user_id', auth()->user()->id)->get();
            return response()->json([
                'message' => 'post deleted',
                'remain-Post' => $remainPost
            ]);
        }

        else{

            return response()->json([
                "message"=>"wrong user"
            ]);
        }

        }
        catch(Exception $e){
            abort(500, "error");
        }
    }
}
