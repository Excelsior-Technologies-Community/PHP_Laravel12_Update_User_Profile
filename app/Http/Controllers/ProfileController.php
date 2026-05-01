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
            'username' => 'nullable|unique:users,username,'.auth()->id(),
            'confirm_password' => 'required_with:password|same:password',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'phone' => 'nullable',
            'city' => 'nullable',
            'bio' => 'nullable',
            'github' => 'nullable',
            'linkedin' => 'nullable',
        ]);

        $user = auth()->user();

        if ($request->hasFile('avatar')) {
            if ($user->avatar && file_exists(public_path('avatars/'.$user->avatar))) {
                unlink(public_path('avatars/'.$user->avatar));
            }

            $fileName = time().'.'.$request->avatar->extension();
            $request->avatar->move(public_path('avatars'), $fileName);

            $user->avatar = $fileName;
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->phone = $request->phone;
        $user->city = $request->city;
        $user->bio = $request->bio;
        $user->github = $request->github;
        $user->linkedin = $request->linkedin;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect('/home')->with('success', 'Profile updated successfully.');
    }

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