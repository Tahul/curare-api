<?php
/** @noinspection PhpSuperClassIncompatibleWithInterfaceInspection */

/* Has this package seems to have a problem w/ PhpStorm inspection; disable the error on this file */

namespace App\Models\Collection;

use App\Models\Link\Link;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Collection extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    public $NAME = 'collections';

    public string $IMAGE_COLLECTION_NAME = 'images';

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'order'
    ];

    protected $hidden = [
        'updated_at',
        'media',
        'links'
    ];

    protected $appends = [
        'links_count',
        'image_url'
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
     * Links relation.
     *
     * @return HasMany
     */
    public function links()
    {
        return $this->hasMany(Link::class);
    }

    /**
     * Get the validation attribute.
     */
    public function getValidationAttribute(): array
    {
        return [
            'store' => [
                'title' => 'required|string',
            ],
            'update' => [
                'title' => 'string'
            ]
        ];
    }

    /**
     * Get the links count attribute.
     *
     * @return int
     */
    public function getLinksCountAttribute()
    {
        return $this->links->count();
    }

    /**
     * Return the `avatar_url` attribute
     *
     * @return string|null
     */
    public function getImageUrlAttribute()
    {
        $media = $this->getFirstMedia($this->IMAGE_COLLECTION_NAME);

        if (!is_null($media)) {
            return $media->getFullUrl();
        }

        return null;
    }
}
