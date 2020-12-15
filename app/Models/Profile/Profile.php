<?php
/** @noinspection PhpSuperClassIncompatibleWithInterfaceInspection */

/* Has this package seems to have a problem w/ PhpStorm inspection; disable the error on this file */

namespace App\Models\Profile;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'user'
    ];

    protected $appends = [
        'name',
        'avatar_url',
        'followers',
        'following',
        'is_followed'
    ];

    /**
     * User relation.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
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
    public function getFollowersAttribute(): string
    {
        return $this->user->followers->count();
    }

    /**
     * Return the `following` attribute
     *
     * @return string
     */
    public function getFollowingAttribute(): string
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
}
