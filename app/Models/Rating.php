<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable=['commentator_id','receiver_id','rating'];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'foreignKey','receiver_id');
    }
}
