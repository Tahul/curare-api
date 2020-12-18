<?php

namespace App\Http\Controllers\Explore;

use App\Http\Controllers\Controller;
use App\Models\Profile\Profile;
use App\Models\User\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class ExploreController extends Controller
{
    protected User $user;

    /**
     * FeedController constructor.
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
     * @return JsonResponse
     */
    public function explore(): JsonResponse
    {
        try {
            $type = ! is_null(request('type')) ? request('type') : 'newcomers';

            $followings = $this->user->followings->pluck('id');

            $query = Profile::whereNotIn('user_id', [...$followings, $this->user->id]);

            if ($type === 'newcomers') {
                $query = $query->latest();
            }

            if ($type === 'mostfollowed') {
                $query = $query->withCount('followers')->orderBy('followers_count', 'desc');
            }

            $query = $query->paginate();

            return response()->json($query);
        } catch (Exception $e) {
            return response()->json([
                'message' => Lang::get('explore.error')
            ], 422);
        }
    }
}
