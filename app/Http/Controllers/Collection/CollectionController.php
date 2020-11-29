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
     * @param $modelId
     * @return JsonResponse
     */
    public function updateImage(CollectionImageUpdateRequest $request, $modelId)
    {
        try {
            $model = $this->model->find($modelId);

            // Delete current avatar
            if (!is_null($model->getFirstMedia($this->model->IMAGE_COLLECTION_NAME))) {
                $model->getFirstMedia($this->model->IMAGE_COLLECTION_NAME)->delete();
            }

            $model->addMedia($request->files->get('avatar'))->toMediaCollection($this->model->IMAGE_COLLECTION_NAME);

            return response()->json(
                array_merge(
                    [
                        'message' => Lang::get('profile.avatar.updated')
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
     * @param $modelId
     * @return JsonResponse
     */
    public function deleteAvatar(Request $request, $modelId)
    {
        try {
            $model = $this->model->find($modelId);

            // Delete current avatar
            if (!is_null($model->getFirstMedia($this->model->IMAGE_COLLECTION_NAME))) {
                $model->getFirstMedia($this->model->IMAGE_COLLECTION_NAME)->delete();
            }

            return response()->json(
                array_merge(
                    [
                        'message' => Lang::get('profile.avatar.removed')
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
