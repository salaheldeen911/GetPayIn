<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Services\ProfileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct(
        protected ProfileService $profileService
    ) {}

    public function show()
    {
        return view('profile.show');
    }

    public function update(UpdateProfileRequest $request)
    {
        try {
            $this->profileService->updateProfile(
                Auth::user(),
                $request->validated(),
                $request->file('avatar'),
                $request->boolean('remove_avatar')
            );

            return back()->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            $this->errorLog($e, 'Failed to update profile');

            return back()->withErrors(['current_password' => $e->getMessage()]);
        }
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();

        try {
            Auth::logout();
            $this->profileService->deleteAccount($user);

            return redirect('/')->with('status', 'Your account has been deleted.');
        } catch (\Exception $e) {
            $this->errorLog($e, 'Failed to delete account');

            return back()->with('error', 'Failed to delete account. '.$e->getMessage());
        }
    }
}
