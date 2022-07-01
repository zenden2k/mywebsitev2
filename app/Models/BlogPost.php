<?php

namespace App\Models;

use App\Helpers\LocaleHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class BlogPost extends Model
{
    use HasFactory, HasSlug, Translatable;

    protected $table = 'blog_posts';

    public $timestamps = false;

    protected $fillable = [
        'category_id',
        'title_ru',
        'title_en',
        'foreword_ru',
        'foreword_en',
        'content_ru',
        'content_en',
        'enable_comments',
        'status',
        'alias',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public function comments()
    {
        return $this->hasMany(BlogComment::class, 'blog_post_id', 'id');
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title_ru')
            ->saveSlugsTo('alias')
            ->slugsShouldBeNoLongerThan(60)
            ->doNotGenerateSlugsOnUpdate();
    }

    public function getUrl($with_host=true, $lang = null): string {
        if ($lang === null) {
            $lang = LocaleHelper::getCurrentLanguage();
        }
        $url = ($lang === 'ru' ? '/ru' : '').'/blog/'.date("Y/m/d", strtotime($this->created_at)).'/'.$this->alias . '-'.$this->id;
        if ( $with_host ) {
            return url($url).((substr( $this->alias ,-1) == '-')?'/':'');
        } else {
            return $url;
        }
    }
}
