<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Support\Facades\Auth;

class ExploreController extends Controller
{
   // In your ExploreController or equivalent
public function explore()
{
    // Fetch all users except the currently authenticated user
    $allUsers = User::where('id', '!=', auth()->id())->get();

    // Pass the variable to the view
    return view('explore', compact('allUsers')); // Make sure to pass 'allUsers'
}

}
