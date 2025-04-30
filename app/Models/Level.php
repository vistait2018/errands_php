<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $fillable=['user_id','level'];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
