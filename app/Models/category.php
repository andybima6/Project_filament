<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',


    ];
// 1 kategori to dapat memiliki many post
    public function post(){
        return $this->hasMany(post::class);
    }
}
