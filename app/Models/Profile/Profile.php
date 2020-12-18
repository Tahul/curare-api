<?php
/** @noinspection PhpSuperClassIncompatibleWithInterfaceInspection */

/* Has this package seems to have a problem w/ PhpStorm inspection; disable the error on this file */

namespace App\Models\Profile;

use App\Models\Relation\Relation;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Profile extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    public string $AVATAR_COLLECTION_NAME = 'avatars';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'description',
        'url'
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'media',
        'user',
        'followers'
    ];

    protected $appends = [
        'name',
        'avatar_url',
        'followers_count',
        'followings_count',
        'is_followed',
        'links_count'
    ];

    /**
     * User relation.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Followers relation.
     *
     * @return HasManyThrough
     */
    public function followers(): HasManyThrough
    {
        return $this->hasManyThrough(Relation::class, User::class, 'id', 'following_id', 'user_id', 'id');
    }

    /**
     * Return the `avatar_url` attribute
     *
     * @return string|null
     */
    public function getAvatarUrlAttribute(): ?string
    {
        $media = $this->getFirstMedia($this->AVATAR_COLLECTION_NAME);

        if (!is_null($media)) {
            return $media->getFullUrl();
        }

        return null;
    }

    /**
     * Return the `name` attribute
     *
     * @return string
     */
    public function getNameAttribute(): string
    {
        return $this->user->name;
    }

    /**
     * Return the `followers` attribute
     *
     * @return string
     */
    public function getFollowersCountAttribute(): string
    {
        return $this->user->followers->count();
    }

    /**
     * Return the `following` attribute
     *
     * @return string
     */
    public function getFollowingsCountAttribute(): string
    {
        return $this->user->followings->count();
    }

    /**
     * Return the `is_following` attribute
     *
     * @return bool
     */
    public function getIsFollowedAttribute(): bool
    {
        $user = auth()->user();

        if (!is_null($user)) {
            return $user->followings->contains($this->id);
        }

        return false;
    }

    /**
     * Get the links count attribute.
     *
     * @return int
     */
    public function getLinksCountAttribute(): int
    {
        return $this->user->links->count();
    }
}
