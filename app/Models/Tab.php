<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Tab
 *
 * @property int $id
 * @property string $title_ru
 * @property string $title_en
 * @property string $url
 * @property int|null $orderNumber
 * @property string|null $alias
 * @property int $active
 * @method static \Illuminate\Database\Eloquent\Builder|Tab newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tab newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tab query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tab whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tab whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tab whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tab whereOrderNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tab whereTitleEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tab whereTitleRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tab whereUrl($value)
 * @mixin \Eloquent
 */
class Tab extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'title_ru',
        'title_en',
        'url',
        'orderNumber',
        'alias',
        'active'
    ];

}
