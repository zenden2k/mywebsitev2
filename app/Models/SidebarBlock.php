<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\SidebarBlock
 *
 * @property int $id
 * @property string $alias
 * @property string $title_ru
 * @property string $title_en
 * @property string $content_ru
 * @property string $content_en
 * @method static \Illuminate\Database\Eloquent\Builder|SidebarBlock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SidebarBlock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SidebarBlock query()
 * @method static \Illuminate\Database\Eloquent\Builder|SidebarBlock whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SidebarBlock whereContentEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SidebarBlock whereContentRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SidebarBlock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SidebarBlock whereTitleEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SidebarBlock whereTitleRu($value)
 * @mixin \Eloquent
 */
class SidebarBlock extends Model
{
    use HasFactory;
    use Translatable;
    use SoftDeletes;

    protected $table = 'sidebarblocks';

    public $timestamps = false;

    protected $fillable = [
        'title_ru',
        'title_en',
        'content_ru',
        'content_en',
        'alias',
    ];
}
