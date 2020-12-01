<?php

namespace App\Http\Controllers\Link;

use App\Http\Controllers\Controller;
use App\Models\Link\Link;
use App\Models\User\User;
use App\Traits\UserResourceController;
use Illuminate\Support\Facades\Auth;

class LinkController extends Controller
{
    use UserResourceController;

    /**
     * LinkController constructor.
     *
     * @param Link $link
     */
    public function __construct(Link $link)
    {
        $this->model = $link;

        $this->middleware(function ($request, $next) {
            $this->user = User::find(
                Auth::id()
            );

            return $next($request);
        });
    }
}
