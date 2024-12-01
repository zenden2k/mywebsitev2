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
        'nickname',
        'email',
        'text',
        'answer'
    ];

    protected $maps = [
        'name' => 'nickname'
    ];

    protected $appends = ['nickname'];

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

    public function getNicknameAttribute()
    {
        return $this->attributes['name'];
    }

    public function setNicknameAttribute($value)
    {
        $this->attributes['name'] = $value;
    }
}
