<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

use App\Models\Post;
use App\Events\PostCreated;

use Validator;
use App\Helpers\Helper;

class PostsController extends Controller
{
    public function getPosts()
    {
        try
        {
            $posts = Post::paginate(25);
            if(!empty($posts))
            {
                return response()->json([
                    'error' => false,
                    'message' => 'Posts of all users.',
                    'posts' => $posts,
                ], 200);
            }
            return response()->json([
                'error' => true,
                'message' => 'No posts to show.',
            ], 401);
        }
        catch(Exception $e)
        {
            return response()->json([
                'error' => true,
                'message' => 'Something went wrong.',
                'data' => $e->getMessage(),
            ], 401);            
        }
    }
    public function viewPosts($id)
    {
        $validator = \Validator::make(['id' => $id], [
            'id' => 'required|numeric',
        ]);
        if($validator->fails())
        {
            return response()->json(['error' => 'Invalid ID provided'], 400);
        }
        try
        {
            $posts = Post::where('id',$id)->with('comments')->get();
            if($posts==[])
            {
                return response()->json([
                    'error' => false,
                    'message' => 'Post does not exist.',
                ], 401);
            }
            return response()->json([
                'error' => true,
                'message' => 'Selected post with comments.',
                'posts' => $posts,
            ], 200);
        }
        catch(Exception $e)
        {
            return response()->json([
                'error' => true,
                'message' => 'Something went wrong.',
                'data' => $e->getMessage(),
            ], 401);            
        }
    }
    public function createPosts(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required'
        ]);
        if($validator->fails())
        {
            return response()->json(['error' => $validator->errors()], 400);
        }
        try
        {
            $posts = new Post;
            $posts->title = $request->title;
            $posts->content = $request->content;
            $posts->user_id = Auth::user()->id;
            $posts->save();

            if($posts)
            {
                PostCreated::dispatch($posts);
                return response()->json([
                    'error' => false,
                    'message' => 'Post created',
                    'post' => $posts,
                ], 200);
            }
            return response()->json([
                'error' => true,
                'message' => 'Unable to create post.',
            ], 401);
        }
        catch(Exception $e)
        {
            return response()->json([
                'error' => true,
                'message' => 'Something went wrong.',
                'data' => $e->getMessage(),
            ], 401);            
        }
    }
    public function updatePosts(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required'
        ]);
        if($validator->fails())
        {
            return response()->json(['error' => $validator->errors()], 400);
        }
        try
        {
            $posts = Post::find($id);
            if($posts->user_id == Auth::user()->id)
            {
                $posts->title = $request->title;
                $posts->content = $request->content;
                if($posts->save())
                {
                    return response()->json([
                        'error' => false,
                        'message' => 'Post updated',
                        'post' => $posts,
                    ], 200);
                }
                return response()->json([
                    'error' => true,
                    'message' => 'Unable to update post.',
                ], 404);
            }
            else
            {
                return response()->json([
                    'error' => true,
                    'message' => 'You are not authorized to edit this post.',
                ], 404);   
            }
        }
        catch(Exception $e)
        {
            return response()->json([
                'error' => true,
                'message' => 'Something went wrong.',
                'data' => $e->getMessage(),
            ], 500);            
        }
    }
    public function deletePosts($id)
    {
        $validator = \Validator::make(['id' => $id], [
            'id' => 'required|numeric',
        ]);
        if($validator->fails())
        {
            return response()->json(['error' => 'Invalid ID provided'], 400);
        }
        try
        {
            $posts = Post::find($id);
            $posts->delete();
            
            if($posts)
            {
                return response()->json([
                    'error' => false,
                    'message' => 'Post deleted',
                    'post' => $posts,
                ], 200);
            }
            return response()->json([
                'error' => true,
                'message' => 'Unable to delete post.',
            ], 401);
        }
        catch(Exception $e)
        {
            return response()->json([
                'error' => true,
                'message' => 'Something went wrong.',
                'data' => $e->getMessage(),
            ], 401);            
        }
    }
    public function getMyPosts()
    {
        try
        {
            $posts = Post::where('user_id', Auth::user()->id)->paginate(25);
            if(!empty($posts))
            {
                return response()->json([
                    'error' => false,
                    'message' => 'All posts of logged in user.',
                    'posts' => $posts,
                ], 200);
            }
            return response()->json([
                'error' => true,
                'message' => 'No posts to show.',
            ], 401);
        }
        catch(Exception $e)
        {
            return response()->json([
                'error' => true,
                'message' => 'Something went wrong.',
                'data' => $e->getMessage(),
            ], 401);            
        }
    }
}
