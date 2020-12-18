<?php

namespace Database\Seeders;

use App\Models\Collection\Collection;
use App\Models\Link\Link;
use App\Models\Profile\Profile;
use App\Models\User\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::flushEventListeners();;

        // Seed users, collections, links
        User::factory(10)
            ->has(
                Collection::factory()->count(2)
                    ->state(function (array $attributes, User $user) {
                        return [
                            'user_id' => $user->id
                        ];
                    })
                    ->has(
                        Link::factory()->count(3)->state(
                            function (array $attributes, Collection $collection) {
                                return [
                                    'user_id' => $collection->user_id,
                                    'collection_id' => $collection->id
                                ];
                            }
                        )
                    )
            )
            ->has(
                Profile::factory()
                    ->state(function (array $attributes, User $user) {
                        return [
                            'user_id' => $user->id
                        ];
                    })
            )
            ->create();

        // Seed user relations
        $users = User::all();

        $users->each(
            function (User $user) use ($users) {
                $user->followings()->attach(
                    $users->whereNotIn('id', [$user->id])->random(5)->pluck('id')
                );

                $user->profile->addMedia($this->getRandomImage($user->id))->preservingOriginal()->toMediaCollection('avatars');
            }
        );
    }

    /**
     * Return a random seeded image path.
     *
     * @param int|null $id
     * @return string
     */
    private function getRandomImage($id = null): string
    {
        $avatarId = is_null($id) || $id > 10 ? rand(1, 10) : $id;

        return resource_path('seeders/avatar_' . $avatarId . '.jpg');
    }
}
