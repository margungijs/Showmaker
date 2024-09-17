<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class CommentController extends Controller
{
    public static function store(Request $request, $user){
        $validation = Validator::make($request->all(), [
            'comment' => 'required',
            'event' => 'required|exists:event_details,id',
            'rating' => 'required|between:1,5'
        ]);

        if($validation->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'The payload is not formatted correctly',
                'errors' => $validation->errors()
            ], 422);
        }

        $data = $validation->validated();

        $u = User::find($user);

        $comment = Comment::create([
            'comment' => $request->comment,
            'user_id' => $user,
            'user' => $u->name,
            'event' => $request->event,
            'rating' => $request->rating
        ]);

        return response()->json([
            'status' => 201,
            'message' => 'Comment successfully created.',
            'comment' => $comment
        ], 201);
    }
}
