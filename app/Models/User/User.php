<?php

namespace App\Models\User;

use App\Models\Collection\Collection;
use App\Models\Link\Link;
use App\Models\Profile\Profile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\NewAccessToken;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
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
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * Return the user collections.
     *
     * @return HasMany
     */
    public function collections(): HasMany
    {
        return $this->hasMany(Collection::class);
    }


    /**
     * Followers relation.
     *
     * @return BelongsToMany
     */
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'relations', 'follower_id', 'following_id');
    }

    /**
     * Following relation.
     *
     * @return BelongsToMany
     */
    public function followings(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'relations', 'following_id', 'follower_id');
    }

    /**
     * Links relation.
     *
     * @return HasMany
     */
    public function links(): HasMany
    {
        return $this->hasMany(Link::class);
    }

    /**
     *  Regenerate the user token.
     *
     * @return NewAccessToken
     */
    public function regenerateToken(): NewAccessToken
    {
        $token = $this->tokens()->where('name', '=', 'access_token')->first();

        if (!is_null($token)) {
            try {
                $token->delete();
            } catch (\Exception $e) {
                info('Failed to remove ' . $this->name . ' existing access_token.');
            }
        }

        return $this->createToken('access_token');
    }


    /**
     * Return the user access token token.
     *
     * @return string
     */
    public function getTokenAttribute(): string
    {
        return $this->regenerateToken()->plainTextToken;
    }
}
