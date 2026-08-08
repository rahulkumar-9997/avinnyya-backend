<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ImageHelper;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function edit(User $profile)
    {
        $this->authorizeOwner($profile);
 
        return view('backend.pages.profile.edit', ['user' => $profile]);
    }
 
    
    public function update(Request $request, User $profile)
    {
        $this->authorizeOwner($profile); 
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $profile->id,
            'phone_number' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female',
            'bio' => 'nullable|string|max:1000',
            'password' => 'nullable|string|min:6|confirmed',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'profile_image.image' => 'The profile photo must be a valid image file.',
            'profile_image.max' => 'The profile photo may not be larger than 2MB.',
        ]);
 
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the errors below and try again.');
        } 
        $validated = $validator->validated();
        DB::beginTransaction(); 
        try {
            $profile->name = $validated['name'];
            $profile->email = $validated['email'];
            $profile->phone_number = $validated['phone_number'] ?? null;
            $profile->gender = $validated['gender'] ?? null;
            $profile->bio = $validated['bio'] ?? null; 
            if (!empty($validated['password'])) {
                $profile->password = Hash::make($validated['password']);
            }
            $oldImage = $profile->profile_img;
            $newImageName = null;
 
            if ($request->hasFile('profile_image')) {
                $fileName = ImageHelper::generateFileName($profile->name, 'user');
                $newImageName = ImageHelper::uploadSingleImageWebpOnly(
                    $request->file('profile_image'),
                    $fileName,
                    'profile',
                    $oldImage
                );
                $profile->profile_img = $newImageName;
            } 
            $profile->save(); 
            DB::commit(); 
            return redirect()->back()->with('success', 'Profile updated successfully!');
        } catch (\Throwable $e) {
            DB::rollBack(); 
            Log::error('Profile update failed for user #' . $profile->id . ': ' . $e->getMessage());
             return redirect()->back()
                ->withInput()
                ->with('error', 'Something went wrong while updating your profile. Please try again.');
        }
    }
     
    protected function authorizeOwner(User $profile): void
    {
        abort_unless(
            Auth::check() && Auth::id() === $profile->id,
            403,
            'You are not authorized to edit this profile.'
        );
    }

    
}
