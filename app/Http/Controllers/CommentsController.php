<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

use App\Models\Comment;

use Validator;
use App\Helpers\Helper;

class CommentsController extends Controller
{
    public function getComments($postId)
    {
        $validator = \Validator::make(['post_id' => $postId], [
            'post_id' => 'required|numeric',
        ]);
        if($validator->fails())
        {
            return response()->json(['error' => 'Invalid ID provided'], 400);
        }
        try
        {
            $comments = Comment::where('post_id', $postId)->paginate(25);
            if(!empty($posts))
            {
                return response()->json([
                    'error' => false,  
                    'message' => 'All comments of the post.',
                    'posts' => $comments,
                ], 200);
            }
            return response()->json([
                'error' => true,
                'message' => 'No comments to show.',
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
    public function createComments(Request $request, $postId)
    {
        $validator_req = \Validator::make($request->all(), [
            'comment' => 'required|string|max:200',
        ]);
        if($validator_req->fails())
        {
            return response()->json(['error' => $validator_req->errors()], 400);
        }
        $validator_id = \Validator::make(['post_id' => $postId], [
            'post_id' => 'required|numeric',
        ]);
        if($validator_id->fails())
        {
            return response()->json(['error' => $validator_id->errors()], 400);
        }
        try
        {
            $comments = new Comment;
            $comments->comment = $request->comment;
            $comments->post_id = $postId;
            $comments->user_id = Auth::user()->id;
            $comments->save();

            if($comments)
            {
                return response()->json([
                    'error' => false,
                    'message' => 'Comment created',
                    'post' => $comments,
                ], 200);
            }
            return response()->json([
                'error' => true,
                'message' => 'Unable to create comment.',
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
    public function updateComments(Request $request, $postId, $commentId)
    {
        $validator_req = \Validator::make($request->all(), [
            'comment' => 'required|string|max:200',
        ]);
        if($validator_req->fails())
        {
            return response()->json(['error' => $validator_req->errors()], 400);
        }
        $validator_id = \Validator::make(['post_id' => $postId], [
            'post_id' => 'required|numeric',
        ]);
        if($validator_id->fails())
        {
            return response()->json(['error' => $validator_id->errors()], 400);
        }
        $validator_comment_id = \Validator::make(['comment_id' => $commentId], [
            'comment_id' => 'required|numeric',
        ]);
        if($validator_comment_id->fails())
        {
            return response()->json(['error' => $validator_comment_id->errors()], 400);
        }
        try
        {
            $comments = Comment::find($commentId);
            if($comments->user_id == Auth::user()->id AND $comments->post_id == $postId)
            {
                $comments->comment = $request->comment;
                $comments->post_id = $postId;
                $comments->user_id = Auth::user()->id;
                $comments->save();

                if($comments)
                {
                    return response()->json([
                        'error' => false,
                        'message' => 'Comment updated',
                        'post' => $comments,
                    ], 200);
                }
                return response()->json([
                    'error' => true,
                    'message' => 'Unable to update comment.',
                ], 401);
            }
            else
            {
                return response()->json([
                    'error' => true,
                    'message' => 'You do not have permission.',
                ], 401);
            }          
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
    public function deleteComments($id)
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
            $comments = Comment::find($id);
            if($comments->user_id == Auth::user()->id)
            {
                if($comments->delete())
                {
                    return response()->json([
                        'error' => false,
                        'message' => 'Comment deleted',
                        'post' => $comments,
                    ], 200);
                }
                else
                {
                    return response()->json([
                        'error' => true,
                        'message' => 'Unable to delete comment.',
                    ], 401);
                }
            }
            else
            {
                return response()->json([
                    'error' => true,
                    'message' => 'You do not have permission.',
                ], 401);
            }
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
