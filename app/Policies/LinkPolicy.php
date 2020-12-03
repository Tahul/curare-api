<?php

namespace App\Policies;

use App\Models\Collection\Collection;
use App\Models\Link\Link;
use App\Models\User\User;

class LinkPolicy
{
    /**
     * Determine if the given collection can be updated by the user.
     *
     * @param User $user
     * @param Link $link
     * @return bool
     */
    public function update(User $user, Link $link)
    {
        return $user->id === $link->user_id;
    }

    /**
     * Determine if the given collection can be deleted by the user.
     *
     * @param User $user
     * @param Link $link
     * @return bool
     */
    public function delete(User $user, Link $link)
    {
        return $user->id === $link->user_id;
    }
}
