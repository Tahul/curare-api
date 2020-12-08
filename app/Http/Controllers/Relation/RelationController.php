<?php

namespace App\Http\Controllers\Relation;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class RelationController extends Controller
{
    protected User $user;

    /**
     * RelationController constructor.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = User::find(
                Auth::id()
            );

            return $next($request);
        });
    }

    /**
     * Follow a user.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function follow(User $user) {
        try {
            $this->user->following()->attach($user->id);

            return response()->json([
                'message' => Lang::get('relations.followed', ['name' => $user->name]),
                'following' => $this->user->followers->all(),
                'followed_count' => $user->following->count()
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => Lang::get('relations.error')
            ], 422);
        }
    }

    /**
     * Unfollow a user.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function unfollow(User $user) {
        try {
            $this->user->following()->detach($user->id);

            return response()->json([
                'message' => Lang::get('relations.followed', ['name' => $user->name]),
                'following' => $this->user->followers->all(),
                'followed_count' => $user->following->count()
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => Lang::get('relations.error')
            ], 422);
        }
    }

    /**
     * Get all the people followed by a user.
     *
     * @param User|null $user
     * @return JsonResponse
     */
    public function following(User $user = null) {
        try {
            $user = !is_null($user) ? $user : $this->user;

            return response()->json($user->following->all());
        } catch (Exception $e) {
            return response()->json([
                'message' => Lang::get('relations.error')
            ], 422);
        }
    }


    /**
     * Get all the people followed by a user.
     *
     * @param User|null $user
     * @return JsonResponse
     */
    public function followers(User $user = null) {
        try {
            $user = !is_null($user) ? $user : $this->user;

            return response()->json($user->followers->all());
        } catch (Exception $e) {
            return response()->json([
                'message' => Lang::get('relations.error')
            ], 422);
        }
    }
}