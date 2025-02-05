<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $profile = Auth::user()->profile; // جلب بروفايل المستخدم الحالي
        return response()->json($profile);
    }

    public function update(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:profiles,email,' . Auth::id(),
            'phone_number' => 'required|string',
            'gender' => 'required|in:Male,Female',
            'date_of_birth' => 'required|date',
            'profile_picture' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
        ]);

        $profile = Auth::user()->profile ?? new Profile(['user_id' => Auth::id()]);
        $profile->fill($request->except('profile_picture'));

        if ($request->hasFile('profile_picture')) {
            // حذف الصورة القديمة إن وجدت
            if ($profile->profile_picture) {
                Storage::disk('public')->delete('profiles/' . $profile->profile_picture);
            }

            $fileName = time() . '.' . $request->profile_picture->extension();
            $request->profile_picture->storeAs('profiles', $fileName, 'public');
            $profile->profile_picture = $fileName;
        }

        $profile->save();

        return response()->json([
            'message' => 'Profile updated successfully!',
            'profile' => $profile
        ]);
    }
}
