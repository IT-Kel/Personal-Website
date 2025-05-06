<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post; // Make sure to import the Post model
use App\Models\User;


class FeedController extends Controller
{
    public function index()
{
    $authUser = User::find(Auth::id()); // Explicitly get the User instance

    if (!$authUser) {
        abort(404, "User not found");
    }

    $authUser->load('followedUsers'); // Ensure relationship is loaded

    $followedUserIds = $authUser->followedUsers->pluck('id')->toArray();
    $followedUserIds[] = $authUser->id;

    $posts = Post::whereIn('user_id', $followedUserIds)
                ->orderBy('created_at', 'desc')
                ->get();

    return view('feed', compact('authUser', 'posts'));
}

    public function show(User $user)
        {
            // Fetch user posts (you can add additional filtering like public posts or only the user's posts)
            $posts = Post::where('user_id', $user->id)->get();

            return view('user.feed', compact('user', 'posts'));
        }


}
