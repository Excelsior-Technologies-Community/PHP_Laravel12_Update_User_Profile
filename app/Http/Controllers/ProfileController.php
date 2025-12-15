<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('profile');
    }

   public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email',
        'confirm_password' => 'required_with:password|same:password',
        'avatar' => 'nullable|image|mimes:jpeg,png,jpg',
        'phone' => 'nullable',
        'city' => 'nullable',
    ]);

    $user = auth()->user();

    // Avatar upload
    if ($request->hasFile('avatar')) {
        $fileName = time().'.'.$request->avatar->extension();
        $request->avatar->move(public_path('avatars'), $fileName);
        $user->avatar = $fileName;
    }

    // Update fields
    $user->name  = $request->name;
    $user->email = $request->email;
    $user->phone = $request->phone;
    $user->city  = $request->city;

    // Password update
    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    $user->save();

    return redirect('/home')->with('success','Profile updated successfully.');

}

}
