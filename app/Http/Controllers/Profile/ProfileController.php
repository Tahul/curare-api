<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\ProfileAvatarUpdateRequest;
use App\Http\Requests\Profile\ProfileUpdateRequest;
use App\Models\Profile;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class ProfileController extends Controller
{
    // Get the current user profile
    private function getCurrentUserProfile(Request $request): Profile
    {
        return $request->user()->profile;
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id = null)
    {
        try {
            $profile = null;

            if (is_null($id)) {
                $profile = $this->getCurrentUserProfile();
            } else {
                $profile = Profile::where('user_id', $id)->firstOrFail();
            }

            return response()->json($profile->toArray());
        } catch (Exception $e) {
            return response()->json([
                'message' => Lang::get('profile.error')
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProfileUpdateRequest $request
     * @return JsonResponse
     */
    public function update(ProfileUpdateRequest $request)
    {
        try {
            $profile = $this->getCurrentUserProfile();

            $profileData = request(['first_name', 'last_name', 'description']);

            $profile->update($profileData);

            return response()->json([
                'message' => Lang::get('profile.updated'),
                ...$profileData
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => Lang::get('profile.error')
            ]);
        }
    }

    /**
     * Update the current user avatar.
     *
     * @param ProfileAvatarUpdateRequest $request
     * @return JsonResponse
     */
    public function updateAvatar(ProfileAvatarUpdateRequest $request)
    {
        return response()->json([
            'message' => 'Avatar updated!'
        ]);

        // TODO: Implement spatie/media-library avatar management
    }

    /**
     * Delete the current user avatar.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteAvatar(Request $request)
    {
        return response()->json([
            'message' => 'Avatar deleted!'
        ]);

        // TODO: Implement spatie/media-library avatar management
    }
}
