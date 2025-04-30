<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bvn extends Model
{
    protected $fillable=['user_id','bvn','is_valid'];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
