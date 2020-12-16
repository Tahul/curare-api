<?php

namespace App\Models\Link;

use App\Casts\Json;
use App\Models\Profile\Profile;
use App\Models\User\User;
use App\Models\Collection\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Link extends Model
{
    use HasFactory;

    public $NAME = 'links';

    protected $fillable = [
        'user_id',
        'collection_id',
        'url',
        'ogp',
        'clicks',
        'order'
    ];

    protected $casts = [
        'ogp' => Json::class
    ];

    protected $hidden = [
        'collection_id'
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
     * Profile relation.
     *
     * @return HasOneThrough
     */
    public function profile(): HasOneThrough
    {
        return $this->hasOneThrough(Profile::class, User::class, 'id', 'user_id', 'user_id', 'id');
    }

    /**
     * Collection relation.
     *
     * @return BelongsTo
     */
    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }

    /**
     * Get the validation attribute.
     */
    public function getValidationAttribute(): array
    {
        return [
            'store' => [
                'url' => 'required|url',
                'collection_id' => 'exists:collections,id',
                'ogp' => 'array|nullable'
            ],
            'update' => [
                'url' => 'url',
                'collection_id' => 'exists:collections,id',
                'ogp' => 'array|nullable'
            ]
        ];
    }
}
