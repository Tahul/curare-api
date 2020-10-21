<?php
/** @noinspection PhpSuperClassIncompatibleWithInterfaceInspection */

/* Has this package seems to have a problem w/ PhpStorm inspection; disable the error on this file */

namespace App\Models;

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
        'user_id',
        'created_at',
        'updated_at',
        'media'
    ];

    protected $appends = [
        'avatar_url'
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
}