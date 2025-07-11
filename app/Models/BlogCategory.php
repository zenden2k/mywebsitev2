<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogCategory extends Model
{
    use HasFactory;
    use Translatable;
    use SoftDeletes;

    protected $table = 'blog_categories';
    public $timestamps = false;

    protected $fillable = [
        'title_ru',
        'title_en',
        'alias',
        'active'
    ];
}
