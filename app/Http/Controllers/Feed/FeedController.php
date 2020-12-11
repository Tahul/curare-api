<?php

namespace App\Http\Controllers\Feed;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use Exception;
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

    public function feed() {
        try {
            //
        } catch (Exception $e) {
            response()->json([
                'message' => Lang::get('feed')
            ]);
        }
    }
}
