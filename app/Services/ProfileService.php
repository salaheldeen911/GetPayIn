<?php

namespace App\Services;

use App\Helpers\ImageHelper;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileService
{
    public function updateProfile(User $user, array $data, ?UploadedFile $avatar = null, bool $removeAvatar = false): User
    {
        // Handle avatar upload
        if ($avatar) {
            // Delete old avatar if exists
            if ($user->avatar) {
                ImageHelper::delete($user->avatar);
            }

            // Store new avatar
            $path = ImageHelper::store($avatar, 'avatars');
            $data['avatar'] = $path;
        }

        // Handle avatar removal
        if ($removeAvatar && $user->avatar) {
            ImageHelper::delete($user->avatar);
            $data['avatar'] = null;
        }

        // Handle password update
        if (isset($data['current_password'])) {
            if (! Hash::check($data['current_password'], $user->password)) {
                throw new \Exception('The provided password does not match your current password.');
            }
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }
        } else {
            unset($data['password']);
        }

        // Remove unnecessary fields
        unset($data['current_password']);
        unset($data['password_confirmation']);
        unset($data['remove_avatar']);

        $user->update($data);

        return $user;
    }

    public function deleteAccount(User $user): void
    {
        if ($user->avatar) {
            $oldPath = str_replace('storage/', '', $user->avatar);
            Storage::disk('public')->delete($oldPath);
        }

        $user->delete();
    }
}
