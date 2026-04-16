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

    // Show profile page
    public function index()
    {
        return view('profile');
    }

    // Update profile
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'confirm_password' => 'required_with:password|same:password',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'phone' => 'nullable',
            'city' => 'nullable',
        ]);

        $user = auth()->user();

        //  Avatar upload (delete old image first)
        if ($request->hasFile('avatar')) {

            // Delete old avatar if exists
            if ($user->avatar && file_exists(public_path('avatars/'.$user->avatar))) {
                unlink(public_path('avatars/'.$user->avatar));
            }

            $fileName = time().'.'.$request->avatar->extension();
            $request->avatar->move(public_path('avatars'), $fileName);

            $user->avatar = $fileName;
        }

        //  Update fields
        $user->name  = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->city  = $request->city;

        //  Password update (only if entered)
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect('/home')->with('success', 'Profile updated successfully.');
    }

    //  DELETE AVATAR 
    public function deleteAvatar()
    {
        $user = auth()->user();

        if ($user->avatar && file_exists(public_path('avatars/'.$user->avatar))) {
            unlink(public_path('avatars/'.$user->avatar));
        }

        $user->avatar = null;
        $user->save();

        return back()->with('success', 'Avatar removed successfully.');
    }
}