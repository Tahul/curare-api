<?php

namespace App\Policies;

use App\Models\Collection\Collection;
use App\Models\User\User;

class CollectionPolicy
{
    /**
     * Determine if the given collection can be updated by the user.
     *
     * @param User $user
     * @param Collection $collection
     * @return bool
     */
    public function update(User $user, Collection $collection)
    {
        return $user->id === $collection->user_id;
    }

    /**
     * Determine if the given collection can be deleted by the user.
     *
     * @param User $user
     * @param Collection $collection
     * @return bool
     */
    public function delete(User $user, Collection $collection)
    {
        return $user->id === $collection->user_id;
    }
}
