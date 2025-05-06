<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Follow;

class FollowController extends Controller
{
    // Ensure only authenticated users can access these methods
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function followUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $userToFollow = User::findOrFail($request->user_id);
        $authUser = Auth::user();

        // Check if the user is already following
        $isFollowing = Follow::where('follower_id', $authUser->id)
            ->where('following_id', $userToFollow->id)
            ->exists();

        if (!$isFollowing) {
            Follow::create([
                'follower_id' => $authUser->id,
                'following_id' => $userToFollow->id,
            ]);

            return response()->json(['message' => 'Followed successfully!']);
        }

        return response()->json(['message' => 'Already following this user.'], 400);
    }

    public function unfollowUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $userToUnfollow = User::findOrFail($request->user_id);
        $authUser = Auth::user();

        // Check if the follow relationship exists
        $follow = Follow::where('follower_id', $authUser->id)
            ->where('following_id', $userToUnfollow->id)
            ->first();

        if ($follow) {
            $follow->delete();
            return response()->json(['message' => 'Unfollowed successfully!']);
        }

        return response()->json(['message' => 'You are not following this user.'], 400);
    }
}
