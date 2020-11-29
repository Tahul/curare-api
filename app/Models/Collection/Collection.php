<?php
/** @noinspection PhpSuperClassIncompatibleWithInterfaceInspection */

/* Has this package seems to have a problem w/ PhpStorm inspection; disable the error on this file */

namespace App\Models\Collection;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'media'
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
        return 0;
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
