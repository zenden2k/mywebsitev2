<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    public $timestamps = false;
    //const CREATED_AT = 'createdAt';
    //const UPDATED_AT = n;
    protected $fillable = [
        'text',
        'email',
        'answer',
        'name',
        'pageId'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->createdAt = $model->freshTimestamp();
        });
    }

    public function page()
    {
        return $this->belongsTo(Page::class, 'pageId');
    }
}
