<?php

namespace App\Http\Controllers\Collection;

use App\Http\Controllers\Controller;
use App\Http\Requests\Collection\CollectionImageUpdateRequest;
use App\Models\Collection\Collection;
use App\Models\User\User;
use App\Traits\UserResourceController;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

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

    /**
     * Update the current user avatar.
     *
     * @param CollectionImageUpdateRequest $request
     * @param Collection $model
     * @return JsonResponse
     */
    public function updateImage(CollectionImageUpdateRequest $request, Collection $model)
    {
        try {
            // Delete current avatar
            if (!is_null($model->getFirstMedia($this->model->IMAGE_COLLECTION_NAME))) {
                $model->getFirstMedia($this->model->IMAGE_COLLECTION_NAME)->delete();
            }

            $model->addMedia($request->files->get('image'))->toMediaCollection($this->model->IMAGE_COLLECTION_NAME);

            return response()->json(
                array_merge(
                    [
                        'message' => Lang::get('collections.updated')
                    ],
                    $model->refresh()->toArray()
                )
            );
        } catch (Exception $e) {
            return response()->json([
                'message' => Lang::get('profile.error')
            ]);
        }
    }

    /**
     * Delete the current user avatar.
     *
     * @param Request $request
     * @param Collection $model
     * @return JsonResponse
     */
    public function deleteImage(Request $request, Collection $model)
    {
        try {
            // Delete current avatar
            if (!is_null($model->getFirstMedia($this->model->IMAGE_COLLECTION_NAME))) {
                $model->getFirstMedia($this->model->IMAGE_COLLECTION_NAME)->delete();
            }

            return response()->json(
                array_merge(
                    [
                        'message' => Lang::get('collections.updated')
                    ],
                    $model->refresh()->toArray()
                )
            );
        } catch (Exception $e) {
            return response()->json([
                'message' => Lang::get('profile.error')
            ]);
        }
    }
}
