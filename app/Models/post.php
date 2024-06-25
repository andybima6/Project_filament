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

    public function category(){
        return $this->belongsTo(category::class);
    }
}
