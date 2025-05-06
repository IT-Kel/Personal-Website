<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Show the profile edit form.
     */
    public function edit()
    {
        // Fetch the logged-in user's data
        $user = Auth::user();

        // Return the profile edit view with the user's data
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();  // Make sure it's a User model instance
        if (!$user instanceof User) {
            return redirect()->route('login')->with('error', 'User is not authenticated.');
        }

        // Validate and update the user's profile data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validation for image upload
            'address' => 'nullable|string|max:500',  // Validation for address
        ]);

        // Update the user's name and email
        $user->name = $request->name;
        $user->email = $request->email;
        $user->address = $request->address;  // Update the address

        // Handle profile image update if a new one is uploaded
        if ($request->hasFile('profile_image')) {
            // Delete the old image if it exists
            if ($user->profile_image && Storage::exists('public/' . $user->profile_image)) {
                Storage::delete('public/' . $user->profile_image);
            }
            
            // Store the new profile image
            $imagePath = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image = $imagePath;
        }

        // Update the user's bio if provided
        if ($request->has('bio')) {
            $user->bio = $request->bio;
        }

        // Save the updated user data
        $user->save();

        // Redirect back with a success message
        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
    }

    /**
     * Show the profile details.
     */
    public function show()
    {
        // Fetch the logged-in user's data
        $user = Auth::user();

        // Return the profile view with the user's data (Use 'profile.edit' if you don't need a separate 'show' view)
        return view('profile.show', compact('user'));
    }
}

