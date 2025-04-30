<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable=['commentator_id','receiver_id','comment'];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
