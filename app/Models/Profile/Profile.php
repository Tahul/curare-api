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
    use HasFactory;
    use InteractsWithMedia;

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
        'media'
    ];

    protected $appends = [
        'name',
        'avatar_url',
        'followers',
        'following'
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
    public function getAvatarUrlAttribute()
    {
        $media = $this->getFirstMedia('avatars');

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
    public function getNameAttribute()
    {
        return $this->user->name;
    }

    /**
     * Return the `followers` attribute
     *
     * @return string
     */
    public function getFollowersAttribute()
    {
        return 0;
    }

    /**
     * Return the `following` attribute
     *
     * return @string
     */
    public function getFollowingAttribute()
    {
        return 0;
    }
}
