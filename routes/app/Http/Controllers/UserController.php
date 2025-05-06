<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    // Search users by name (suggested to keep this)
    public function search(Request $request)
    {
        $query = $request->input('query');

        // Search for users by name
        $users = User::where('name', 'like', "%{$query}%")
                     ->get(['id', 'name']); // Return only id and name

        return response()->json($users);
    }

    // Ensure that the user is authenticated before performing any operations
    public function __construct()
    {
        $this->middleware('auth'); // Ensures the user is logged in
    }

    // Show the settings page for the logged-in user
    public function showSettings()
    {
        $user = Auth::user(); // Get the currently authenticated user

        // If no user is authenticated, redirect to login page
        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in to access settings.');
        }

        return view('settings', compact('user'));
    }

    public function updatePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|confirmed',
    ]);

    $user = Auth::user();

    if (!$user || !($user instanceof \App\Models\User)) {
        return redirect()->route('login')->with('error', 'Please log in to update your password.');
    }

    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'Current password is incorrect.']);
    }

    $user->password = Hash::make($request->new_password);

    try {
        $user->save(); // This should now work
        return redirect()->route('settings')->with('success', 'Password updated successfully.');
    } catch (\Exception $e) {
        return back()->withErrors(['error' => 'An error occurred while updating the password: ' . $e->getMessage()]);
    }
}

    
 

    // Delete the account for the logged-in user
    public function deleteAccount(Request $request)
    {
        // Get the currently authenticated user
        $user = Auth::user();

        // Ensure user is authenticated
        if (!$user) {
            return redirect()->route('login')->with('error', 'You are not authenticated to delete your account.');
        }

        try {
            // Check if user object is indeed an instance of User model
            if ($user instanceof User) {
                $user->delete();  // Attempt to delete the user
            } else {
                return back()->withErrors(['error' => 'Failed to delete the account. Please try again later.']);
            }
        } catch (\Exception $e) {
            // Handle any errors that may occur
            return back()->withErrors(['error' => 'An error occurred while deleting your account: ' . $e->getMessage()]);
        }

        // Log out the user after account deletion
        Auth::logout();

        // Redirect to login page after successful deletion
        return redirect()->route('login')->with('success', 'Your account has been deleted successfully.');
    }

    public function explore()
    {
        $user = Auth::user();
    
        // Get the IDs of users the current user is following (many-to-many relationship)
        $followingIds = $user->following->pluck('id')->toArray();  // Get the 'id' of users being followed
        
        // Suggested users = those not followed by the user and not the user themselves
        $suggestedUsers = User::whereNotIn('id', array_merge($followingIds, [$user->id]))->get();
    
        // Followed users = those who are in the list of followed users
        $followedUsers = User::whereIn('id', $followingIds)->get();
    
        return view('explore', compact('suggestedUsers', 'followedUsers'));
    }
    

    public function feed(User $user)
    {
        return view('user.profile', compact('user'));
    }

    
    
}
