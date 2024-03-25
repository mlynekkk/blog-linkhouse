<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'guid',
        'title',
        'link',
        'description',
        'category'
    ];
}
