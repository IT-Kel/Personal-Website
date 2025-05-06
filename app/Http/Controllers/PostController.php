<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class PostController extends Controller
{
    // Display posts on the dashboard
    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->get();
        return view('dashboard', compact('posts'));
    }

    // Store new post
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'content' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);
        
        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads', 'public'); 
        }

        // Create post
        try {
            $post = new Post(); // ✅ Define $post before using it
            $post->user_id = Auth::id();
            $post->content = htmlspecialchars($request->input('content')); // Sanitizing content
            $post->media = $imagePath; // Assign image path
            $post->save(); // ✅ Save post before redirecting

            // Redirect with success message
            return redirect()->back()->with('success', 'Post added successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['post' => 'Failed to create post.']);
        }
    }


    public function like(Post $post)
{
    $user = auth()->user();

    // Toggle the like (add or remove)
    if ($post->usersWhoLiked()->where('user_id', $user->id)->exists()) {
        // User already liked the post, so unlike it
        $post->usersWhoLiked()->detach($user->id);
    } else {
        // User hasn't liked the post, so like it
        $post->usersWhoLiked()->attach($user->id);
    }

    // Return the updated like count
    return response()->json([
        'likes' => $post->usersWhoLiked()->count(),
        'likedByUser' => $post->usersWhoLiked()->where('user_id', $user->id)->exists()
    ]);
}


public function destroy($id)
{
    $post = Post::findOrFail($id);

    if ($post->user_id !== Auth::id()) {
        return redirect()->back()->with('error', 'Unauthorized action.');
    }

    $post->delete();

    return redirect()->back()->with('success', 'Post deleted successfully.');
}

public function edit(Post $post)
{
    // Ensure only the owner can edit the post
    if ($post->user_id !== auth()->id()) {
        return redirect()->route('dashboard')->with('error', 'Unauthorized action.');
    }

    return view('posts.edit', compact('post'));
}

public function update(Request $request, Post $post)
{
    // Ensure the logged-in user is the owner of the post
    if ($post->user_id !== auth()->id()) {
        return redirect()->route('dashboard')->with('error', 'Unauthorized action.');
    }

    // Validate the incoming request
    $request->validate([
        'content' => 'required|string|max:500',
        'media' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,mp4,webm,ogg,mkv|max:10240', // Media validation (optional)
    ]);

    // Handle media upload if there is one
    if ($request->hasFile('media')) {
        // Delete the old media if it exists
        if ($post->media) {
            Storage::delete('public/uploads/' . basename($post->media));
        }

        // Store the new media
        $mediaPath = $request->file('media')->store('uploads', 'public');
        $post->media = $mediaPath;
    }

    // Update the post content
    $post->update([
        'content' => $request->content,
    ]);

    // Redirect back to the feed
    return redirect()->route('feed')->with('success', 'Post updated successfully.');
}


}
