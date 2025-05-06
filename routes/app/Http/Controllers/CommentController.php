<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, $postId)
{
    // Validate the request
    $request->validate([
        'content' => 'required|string|max:1000',
    ]);

    // Create the comment
    $comment = new Comment();
    $comment->user_id = auth()->user()->id;
    $comment->post_id = $postId;
    $comment->content = $request->content;

    // Save the comment
    $comment->save();

    return redirect()->back()->with('success', 'Comment posted successfully!');
}

public function destroy(Comment $comment)
{
    // Check if the logged-in user is the comment owner
    if ($comment->user_id !== auth()->id()) {
        return redirect()->route('feed')->with('error', 'Unauthorized action.');
    }

    $comment->delete();
    return back()->with('success', 'Comment deleted successfully.');
}

public function reply(Request $request, $commentId)
{
    // Validate the reply
    $request->validate([
        'content' => 'required|string|max:1000',
    ]);

    // Find the parent comment
    $parentComment = Comment::findOrFail($commentId);

    // Create the reply
    $reply = new Comment();
    $reply->user_id = auth()->user()->id;
    $reply->post_id = $parentComment->post_id;
    $reply->content = $request->content;
    $reply->parent_id = $commentId; // Set the parent_id to create a reply

    // Save the reply
    $reply->save();

    return redirect()->back()->with('success', 'Reply posted successfully!');
}   
}
