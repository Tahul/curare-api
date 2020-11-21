<?php

namespace App\Observers;

use App\Models\Profile\Profile;
use App\Models\User\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param User $user
     * @return void
     */
    public function created(User $user)
    {
        // Create the user profile
        Profile::create([
            'user_id' => $user->id
        ]);
    }
}
