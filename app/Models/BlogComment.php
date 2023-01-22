<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogComment extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'blog_post_id',
        'name',
        'email',
        'text',
        'answer'
    ];


    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->createdAt = $model->freshTimestamp();
        });
    }

    public function post()
    {
        return $this->belongsTo(BlogPost::class, 'blog_post_id');
    }
}
