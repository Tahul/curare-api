<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Request;

class AuthController extends Controller
{
    /**
     * Authenticates the user using email and password.
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = request(['email', 'password']);

        try {
            if (Auth::attempt($credentials)) {
                $user = User::find(Auth::user()->id);

                if (!is_null($request->withToken)) {
                    $user = $user->append('token');
                }

                return response()->json(
                    $user
                );
            }

            return response()->json([
                'message' => Lang::get('auth.failed')
            ], 401);
        } catch (Exception $e) {
            return response()->json([
                'message' => Lang::get('auth.error')
            ], 422);
        }
    }

    /**
     * Register the user using name, email and password.
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $credentials = request(['name', 'email', 'password']);

        try {
            $user = User::create([
                'name' => $credentials['name'],
                'email' => $credentials['email'],
                'password' => Hash::make($credentials['password'])
            ]);

            if (Auth::attempt($credentials)) {
                return response()->json(
                    array_merge(
                        $user->toArray(),
                        [
                            'message' => Lang::get('auth.welcome')
                        ]
                    )
                );
            }

            return response()->json([
                'message' => Lang::get('auth.failed')
            ], 401);
        } catch (Exception $e) {
            return response()->json([
                'message' => Lang::get('auth.error')
            ], 422);
        }
    }

    /**
     * Logout the user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            Auth::guard('web')->logout();

            return response()->json([
                'message' => Lang::get('auth.logout')
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => Lang::get('auth.error')
            ], 422);
        }
    }
}
