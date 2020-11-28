<?php

namespace App\Models\Collection;

use App\Models\User\User;
use App\Traits\UserResourceModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Collection extends Model
{
    use HasFactory, UserResourceModel;

    public $name = 'collections';

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'order'
    ];

    protected $hidden = [
        'updated_at'
    ];

    protected $appends = [
        'linksCount'
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
}
