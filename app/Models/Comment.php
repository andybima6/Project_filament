<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    public $guarded = [];

    public function commentable(){
        return $this->morphTo();
    }
    public function user(){
        return $this->BelongsTo(user::class);
    }
    public function comments(){
        // 1 to many
        return $this->morphMany(comment::class,'commentable');
    }
}
