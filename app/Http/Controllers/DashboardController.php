<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Caption;
use App\Models\Gallery;
use App\Models\Post;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // Fetch logged-in user

        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in to access the dashboard.');
        }

        // Fetch captions and gallery posts (same as before)
        $captions = Caption::selectRaw("'caption' AS type, id, user_id, content, NULL AS media, created_at");
        $gallery = Gallery::selectRaw("'gallery' AS type, id, user_id, content, media, created_at");

        // Combine and order posts (same as before)
        $posts = $captions->unionAll($gallery)->orderByDesc('created_at')->get();

        // Fetch posts from the Post model (this needs to be filtered)
        $followedUserIds = DB::table('follows')
            ->where('follower_id', $user->id)
            ->pluck('following_id')
            ->toArray();

        // Get posts from followed users + your own posts
        $postsFromFollowedUsers = Post::whereIn('user_id', $followedUserIds)
            ->orWhere('user_id', $user->id) // Include your own posts
            ->orderByDesc('created_at')
            ->get();

        // Merge the posts with captions and gallery posts (optional)
        $posts = $posts->merge($postsFromFollowedUsers);

        // Eager load the user relationship to optimize queries
        $posts->load('user');

        // Get users to follow (excluding logged-in user & followed users)
        $usersToFollow = User::whereNotIn('id', $followedUserIds)
            ->where('id', '!=', $user->id)
            ->orderBy('name')
            ->get();

        // Pass data to the view
        return view('dashboard', compact('user', 'posts', 'usersToFollow'));
    }
}
