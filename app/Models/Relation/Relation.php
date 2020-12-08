<?php

namespace App\Models\Relation;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Relation extends Model
{
    use HasFactory;

    /**
     * Follower relation.
     *
     * @return BelongsTo
     */
    public function follower() {
        return $this->belongsTo(User::class, 'id', 'follower_id');
    }

    /**
     * Following relation.
     *
     * @return BelongsTo
     */
    public function following() {
        return $this->belongsTo(User::class, 'id', 'following_id');
    }
}
