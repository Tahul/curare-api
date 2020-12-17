<?php

namespace App\Observers;

use App\Models\Profile\Profile;
use App\Models\User\User;
use Exception;

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
        $profile = Profile::create([
            'user_id' => $user->id
        ]);

        try {
            $profile->addMediaFromUrl('https://avatars.dicebear.com/4.5/api/human/' . $user->name . '.svg')->toMediaCollection('avatars');
        } catch (Exception $e) {
            // Mitigate this error as failing to add the default avatar breaks nothing in that logic.
            info('Failed to add default avatar to ' . $user->name);
        }
    }
}
