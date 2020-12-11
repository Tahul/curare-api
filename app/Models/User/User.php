<?php

namespace App\Models\User;

use App\Models\Collection\Collection;
use App\Models\Profile\Profile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'id',
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        // Hide relations as we don't need them from here.
        'profile',
        'collections',
        'followers',
        'following',
        'pivot'
    ];

    /**
     * Return the user profile relation.
     *
     * @return HasOne
     */
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * Return the user collections.
     *
     * @return HasMany
     */
    public function collections()
    {
        return $this->hasMany(Collection::class);
    }


    /**
     * Followers relation.
     *
     * @return BelongsToMany
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'relations', 'follower_id', 'following_id');
    }

    /**
     * Following relation.
     *
     * @return BelongsToMany
     */
    public function followings()
    {
        return $this->belongsToMany(User::class, 'relations', 'following_id', 'follower_id');
    }
}
