<?php

namespace App\Models\Link;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Link extends Model
{
    use HasFactory;

    public $NAME = 'collections';

    protected $fillable = [
        'user_id',
        'collection_id',
        'url',
        'ogp',
    ];

    protected $casts = [
        'ogp' => 'json',
    ];

    protected $hidden = [
        'collection_id'
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
     * Collection relation.
     *
     * @return BelongsTo
     */
    public function collection()
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
                'collection_id' => 'exists:collections,id'
            ],
            'update' => [
                'url' => 'url',
                'collection_id' => 'exists:collections,id'
            ]
        ];
    }
}
