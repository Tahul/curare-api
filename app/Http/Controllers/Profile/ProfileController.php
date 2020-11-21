<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\ProfileAvatarUpdateRequest;
use App\Http\Requests\Profile\ProfileUpdateRequest;
use App\Models\Profile\Profile;
use App\Models\User\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class ProfileController extends Controller
{
    // The avatars collection used for file storage
    private string $AVATAR_COLLECTION_NAME = 'avatars';

    // Get the current user profile
    private function getCurrentUserProfile(Request $request): Profile
    {
        return $request->user()->profile;
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param int|null $id
     * @return JsonResponse
     */
    public function show(Request $request, $id = null)
    {
        try {
            $profile = null;

            if (is_null($id)) {
                $profile = $this->getCurrentUserProfile($request);
            } else {
                $profile = User::where('name', $id)->firstOrFail()->profile;
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
            $profile = $this->getCurrentUserProfile($request);

            $profileData = request(['first_name', 'last_name', 'description', 'url']);

            $profile->update($profileData);

            return response()->json(
                array_merge(
                    [
                        'message' => Lang::get('profile.updated'),
                    ],
                    $profileData
                )
            );
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
        try {
            $profile = $this->getCurrentUserProfile($request);

            // Delete current avatar
            if (!is_null($profile->getFirstMedia($this->AVATAR_COLLECTION_NAME))) {
                $profile->getFirstMedia($this->AVATAR_COLLECTION_NAME)->delete();
            }

            $profile->addMedia($request->files->get('avatar'))->toMediaCollection($this->AVATAR_COLLECTION_NAME);

            return response()->json(
                array_merge(
                    [
                        'message' => Lang::get('profile.avatar.updated')
                    ],
                    $profile->refresh()->toArray()
                )
            );
        } catch (Exception $e) {
            return response()->json([
                'message' => Lang::get('profile.error')
            ]);
        }
    }

    /**
     * Delete the current user avatar.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteAvatar(Request $request)
    {
        try {
            $profile = $this->getCurrentUserProfile($request);

            // Delete current avatar
            if (!is_null($profile->getFirstMedia($this->AVATAR_COLLECTION_NAME))) {
                $profile->getFirstMedia($this->AVATAR_COLLECTION_NAME)->delete();
            }

            return response()->json(
                array_merge(
                    [
                        'message' => Lang::get('profile.avatar.removed')
                    ],
                    $profile->refresh()->toArray()
                )
            );
        } catch (Exception $e) {
            return response()->json([
                'message' => Lang::get('profile.error')
            ]);
        }
    }
}
