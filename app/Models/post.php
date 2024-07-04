<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'thumbnail',
        'color',
        'slug',
        'content',
        'tags',
        'published',
        'category_id',

    ];

    // digunakan ketika ingin menyimpan data nya sebagai json,csv
    protected $casts = [
        'tags' => 'array',
    ];

    // one to many
    public function category(){
        return $this->belongsTo(category::class);
    }
    public function authors()
    {
        return  $this->belongsToMany(User::class,'post_user')->withPivot(['order'])->withTimestamps();
    }
    public function comments(){
        // 1 to many
        return $this->morphMany(comment::class,'commentable');
    }
}
