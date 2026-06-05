<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\ActivityLog;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Show profile page
    public function index()
    {
        $user = auth()->user();
        $profileCompletion = $this->calculateProfileCompletion($user);
        
        return view('profile', compact('profileCompletion'));
    }

    // Update profile
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'confirm_password' => 'required_with:password|same:password',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'phone' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
        ]);

        $user = auth()->user();
        $oldData = $user->getOriginal();

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && file_exists(public_path('avatars/' . $user->avatar))) {
                unlink(public_path('avatars/' . $user->avatar));
            }
            
            $fileName = time() . '_' . uniqid() . '.' . $request->avatar->extension();
            $request->avatar->move(public_path('avatars'), $fileName);
            $user->avatar = $fileName;
        }

        // Update other fields
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->city = $request->city;

        // Update password if provided
        $passwordChanged = false;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $passwordChanged = true;
        }

        $user->save();
        
        // Log activity
        $this->logActivity($user, $oldData, $passwordChanged);

        $message = $passwordChanged 
            ? 'Profile updated successfully! Please login again with your new password.'
            : 'Profile updated successfully!';

        return redirect()->route('user.profile')->with('success', $message);
    }

    // Delete avatar
    public function deleteAvatar(Request $request)
    {
        $user = auth()->user();
        
        if ($user->avatar) {
            $avatarPath = public_path('avatars/' . $user->avatar);
            if (file_exists($avatarPath)) {
                unlink($avatarPath);
            }
            $user->avatar = null;
            $user->save();
            
            $this->logActivity($user, ['avatar' => 'deleted'], false);
            
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Avatar deleted successfully']);
            }
            
            return back()->with('success', 'Avatar deleted successfully!');
        }
        
        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => 'No avatar found'], 404);
        }
        
        return back()->with('error', 'No avatar found to delete');
    }

    // Calculate profile completion percentage
    private function calculateProfileCompletion($user)
    {
        $fields = [
            'name' => !empty($user->name),
            'email' => !empty($user->email),
            'phone' => !empty($user->phone),
            'city' => !empty($user->city),
            'avatar' => !empty($user->avatar),
        ];
        
        $filledFields = count(array_filter($fields));
        $totalFields = count($fields);
        
        return round(($filledFields / $totalFields) * 100);
    }

    // Log user activity
    private function logActivity($user, $oldData, $passwordChanged)
    {
        // Create activity log file or database entry
        $logEntry = [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'email' => $user->email,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'updated_fields' => array_keys(array_diff($user->toArray(), $oldData)),
            'password_changed' => $passwordChanged,
            'timestamp' => now()->toDateTimeString()
        ];
        
        // Store in storage/logs/profile_activity.log
        $logFile = storage_path('logs/profile_activity.log');
        $logContent = json_encode($logEntry) . PHP_EOL;
        file_put_contents($logFile, $logContent, FILE_APPEND);
    }
}