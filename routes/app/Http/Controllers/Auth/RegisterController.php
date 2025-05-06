<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bio' => 'nullable|string|max:255',  // Allow bio to be optional
        ]);

        if ($validator->fails()) {
            // Redirect back with alert messages
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Registration failed. Please check your input.');
        }

        // Create User
        $user = new User();
        $user->name = $request->name;
        $user->address = $request->address;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        // Handling the bio (if provided, otherwise set to empty string)
        $user->bio = $request->bio ?? '';  // Default to empty string if bio is not provided

        // Handling the profile image
        if ($request->hasFile('profile_image')) {
            // Generate a unique image name
            $imageName = 'profile_' . time() . '.' . $request->profile_image->extension();
            
            // Store the image in the 'uploads' folder inside 'storage/app/public'
            $path = $request->profile_image->storeAs('public/uploads', $imageName);
            
            // Store only the relative path in the database
            $user->profile_image = 'uploads/' . $imageName;
        }

        // Save the user to the database
        $user->save();

        // Redirect back with success message
        return redirect()->route('index') // Change 'index' to your actual route name
            ->with('success', 'User registered successfully!');
    }
}
