<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use InvalidArgumentException;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Get the social redirect payload
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getSocialRedirect(Request $request)
    {
        if ($request->get('redirect_url') !== null) {
            return response()->json([
                "redirect_url" => Socialite::driver('twitter')->redirectUrl($request->get('redirect_url'))->stateless()->redirect()->getTargetUrl()
            ]);
        } else {
            return response()->json([
                'message' => Lang::get('auth.failed')
            ], 401);
        }
    }

    /**
     * Get the callback paylaod
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getSocialCallback(Request $request)
    {
        // Retrieve user from socialite callback
        $user = Socialite::with('twitter')
            ->redirectUrl($request->get('redirect_url'))
            ->stateless()
            ->user();

        info($user);

        /*
        // Try to find existing user
        $dbUser = User::where('email', '=', $user->email)->first();
        $token = null;

        // No existing user, create one from Google data
        if (!is_null($user->email) && is_null($dbUser)) {
            $newUser = new User;
            $newUser->email = $user->email;

            if (array_key_exists('family_name', $user->user)) {
                $newUser->lastname = $user->user['family_name'];
            }

            if (array_key_exists('given_name', $user->user) && !is_null($user->user['given_name'])) {
                $newUser->firstname = $user->user['given_name'];
            }

            $newUser->google_id = $user->id;
            $newUser->save();

            $dbUser = $newUser;
        }
        */
    }
}
