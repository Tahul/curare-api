<?php

namespace App\Http\Controllers\Link;

use App\Http\Controllers\Controller;
use App\Models\Collection\Collection;
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

    /**
     * Get all the user request resource.
     *
     * @return mixed
     */
    public function index()
    {
        // Default the source to the current logged in user
        $source = $this->user;

        // Define which is the requested links source depending on GET parameters
        if (request()->has('collectionId')) {
            $source = Collection::find(request()->collectionId);
        } else if (request()->has('userId')) {
            $source = User::find(request()->userId);
        }

        $data = $source->getRelationValue($this->model->NAME);

        return response()->json(
            !is_null($data) ? $data : []
        );
    }
}
