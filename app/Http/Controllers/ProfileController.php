<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
{
    $user = $request->user();

    // 1. Lấy toàn bộ dữ liệu từ form
    $data = $request->validated();

    // 2. XỬ LÝ UPLOAD HÌNH ẢNH (NẾU CÓ)
    if ($request->hasFile('profile_picture')) {
        // Đẩy thẳng lên Cloudinary
        $cloudinary = new \Cloudinary\Cloudinary(env('CLOUDINARY_URL'));
        $uploadedImage = $cloudinary->uploadApi()->upload(
            $request->file('profile_picture')->getRealPath(),
            ['folder' => 'warehouse_profiles'] // Lưu vào folder riêng cho profile
        );

        // Lấy link ảnh Cloudinary gán vào DB
        $data['profile_picture'] = $uploadedImage['secure_url'];
    }

    // 3. Lưu toàn bộ dữ liệu vào Database
    $user->fill($data);

    // 4. Reset xác thực Email nếu Email bị thay đổi
    if ($user->isDirty('email')) {
        $user->email_verified_at = null;
    }

    $user->save();

    return Redirect::route('profile.edit')->with('status', 'profile-updated');
}
    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    
}
