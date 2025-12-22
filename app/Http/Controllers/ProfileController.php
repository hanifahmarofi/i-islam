<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Imported for deleting old images if needed
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user(); 
        
        if (!$user) {
             $user = new User([
                'full_name' => 'Guest Student',
                'matric_id' => '000000',
                'email' => 'guest@school.edu',
                'total_points' => 0
             ]);
        }

        return view('profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        // 1. Validate
        $request->validate([
            'age' => 'required|integer|min:4|max:18',
            'favourite_food' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'parents_phone' => 'nullable|string|max:20',
            // We relax the image rule because we might receive a text string instead
            'profile_picture' => 'nullable', 
        ]);

        $dataToUpdate = $request->only(['age', 'favourite_food', 'mother_name', 'father_name', 'parents_phone']);

        // --- NEW LOGIC: Handle Base64 Image (The Cropped Version) ---
        if ($request->filled('cropped_image')) {
            
            // Delete old image if exists
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            // 1. Get the base64 string
            $base64_image = $request->input('cropped_image'); // e.g. "data:image/jpeg;base64,..."
            
            // 2. Clean up the string (remove the "data:image/jpeg;base64," part)
            if (preg_match('/^data:image\/(\w+);base64,/', $base64_image, $type)) {
                $base64_image = substr($base64_image, strpos($base64_image, ',') + 1);
                $type = strtolower($type[1]); // jpg, png, etc.

                // 3. Decode back to binary image data
                $image_data = base64_decode($base64_image);

                // 4. Generate a unique filename
                $filename = 'avatars/' . time() . '_' . uniqid() . '.' . $type;

                // 5. Save to Storage
                Storage::disk('public')->put($filename, $image_data);

                // 6. Add path to database array
                $dataToUpdate['profile_picture'] = $filename;
            }
        } 
        // Fallback: If they somehow bypassed cropping and sent a raw file
        elseif ($request->hasFile('profile_picture')) {
             if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $path = $request->file('profile_picture')->store('avatars', 'public');
            $dataToUpdate['profile_picture'] = $path;
        }

        // Update Database
        $user->update($dataToUpdate);

        return back()->with('success', 'Profile updated successfully!');
    }
}