<?php

namespace App\Http\Controllers\Collection;

use App\Http\Controllers\Controller;
use App\Models\Collection\Collection;
use App\Models\User\User;
use App\Traits\UserResourceController;
use Illuminate\Support\Facades\Auth;

class CollectionController extends Controller
{
    use UserResourceController;

    /**
     * CollectionController constructor.
     *
     * @param Collection $collection
     */
    public function __construct(Collection $collection)
    {
        $this->model = $collection;

        $this->middleware(function ($request, $next) {
            $this->user = User::find(
                Auth::id()
            );

            return $next($request);
        });
    }
}
