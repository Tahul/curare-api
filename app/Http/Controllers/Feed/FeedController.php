<?php

namespace App\Http\Controllers\Feed;

use App\Http\Controllers\Controller;
use App\Models\Link\Link;
use App\Models\User\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class FeedController extends Controller
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
    public function feed(): JsonResponse
    {
        try {
            $followings = $this->user->followings->pluck('id');

            $query = Link::with('collection')->with('profile')->whereIn('user_id', $followings)->latest('created_at')->paginate();

            return response()->json($query);
        } catch (Exception $e) {
            return response()->json([
                'message' => Lang::get('feed.error')
            ], 422);
        }
    }
}
