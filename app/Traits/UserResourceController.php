<?php

namespace App\Traits;

use App\Models\User\User;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

trait UserResourceController
{
    protected Model $model;

    protected User $user;

    /**
     * Get all the user request resource.
     *
     * @return mixed
     */
    public function index()
    {
        $user = $this->user;

        $data = null;

        try {
            if (request()->has('userId')) {
                $user = User::find(request()->userId);
            }

            $data = $user->getRelationValue($this->model->NAME);
        } catch (Exception $e) {
            return response()->json([
                'message' => Lang::get($this->model->NAME . '.error')
            ]);
        }

        return response()->json(
            !is_null($data) ? $data : []
        );
    }

    /**
     * Store the user resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        // Validating through model
        $validator = Validator::make($request->all(), $this->model->validation['store']);

        // Validator checks
        if ($validator->fails()) {
            return response()->json([
                'message' => Lang::get($this->model->NAME . '.error'),
                'errors' => $validator->errors()
            ], 400);
        }

        // Create the model & return it
        try {
            $this->model->fill(
                array_merge(
                    $request->all(),
                    ['user_id' => $this->user->id]
                )
            );

            $this->model->save();

            return response()->json(
                array_merge(
                    [
                        'message' => Lang::get($this->model->NAME . '.created')
                    ],
                    $this->model->toArray()
                )
            );
        } catch (Exception $e) {
            return response()->json([
                'message' => Lang::get($this->model->NAME . '.error')
            ], 422);
        }
    }

    /**
     * Update the user resource.
     *
     * @param Request $request
     * @param mixed $modelId
     * @return JsonResponse
     */
    public function update(Request $request, $modelId)
    {
        $model = $this->model->find($modelId);

        // Validating through model
        $validator = Validator::make($request->all(), $this->model->validation['update']);

        // Validator checks
        if ($validator->fails()) {
            return response()->json([
                'message' => Lang::get($this->model->NAME . '.error'),
                'errors' => $validator->errors()
            ], 400);
        }

        // Update the model & return it
        try {
            if ($this->user->can('update', $model)) {
                $model->update(
                    $request->all()
                );

                return response()->json(
                    array_merge(
                        [
                            'message' => Lang::get($this->model->NAME . '.updated')
                        ],
                        $model->toArray()
                    )
                );
            } else {
                return response()->json([
                    'message' => Lang::get($this->model->NAME . '.permission')
                ], 403);
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => Lang::get($this->model->NAME . '.error')
            ], 422);
        }
    }

    /**
     * Delete the user resource.
     *
     * @param Request $request
     * @param mixed $modelId
     * @return JsonResponse
     */
    public function delete(Request $request, $modelId)
    {
        // Update the model & return it
        try {
            $model = $this->model->find($modelId);

            if ($this->user->can('delete', $model)) {
                $model->delete();

                return response()->json([
                    'message' => Lang::get($this->model->NAME . '.deleted'),
                    'deleted' => $modelId
                ]);
            } else {
                return response()->json([
                    'message' => Lang::get($this->model->NAME . 'permission')
                ], 403);
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => Lang::get($this->model->NAME . '.error')
            ], 422);
        }
    }
}
