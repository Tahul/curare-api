<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Get the social redirect payload
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getSocialRedirect(Request $request): JsonResponse
    {
        if ($request->has('redirect_url') && $request->has('type')) {
            return response()->json([
                "redirect_url" => Socialite::driver($request->type)->redirect()->getTargetUrl()
            ]);
        } else {
            return response()->json([
                'message' => Lang::get('auth.social.failed')
            ], 401);
        }
    }

    /**
     * Get the callback paylaod
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getSocialCallback(Request $request): JsonResponse
    {
        // Retrieve user from socialite callback
        $user = Socialite::with($request->type)
            ->stateless()
            ->user();

        info($user);
    }
}
