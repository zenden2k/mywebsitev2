<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;
    protected $table = 'menuitems';
    public $timestamps = false;

    protected $fillable = [
        'title_ru',
        'title_en',
        'url_ru',
        'url_en',
        'target_page_id',
        'tab_id',
        'orderNumber',
        'status'
    ];

    public function targetPage()
    {
        return $this->belongsTo(Page::class, 'target_page_id');
    }

    public function tab()
    {
        return $this->belongsTo(Tab::class, 'tab_id');
    }
}
