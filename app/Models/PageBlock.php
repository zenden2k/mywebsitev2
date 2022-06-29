<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PageBlock
 *
 * @property int $id
 * @property int $page_id
 * @property string $title_ru
 * @property string|null $title_en
 * @property string|null $content_ru
 * @property string|null $content_en
 * @property int|null $orderNumber
 * @property int|null $showInSidebar
 * @property string|null $alias
 * @method static \Illuminate\Database\Eloquent\Builder|PageBlock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PageBlock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PageBlock query()
 * @method static \Illuminate\Database\Eloquent\Builder|PageBlock whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageBlock whereContentEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageBlock whereContentRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageBlock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageBlock whereOrderNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageBlock wherePageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageBlock whereShowInSidebar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageBlock whereTitleEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageBlock whereTitleRu($value)
 * @mixin \Eloquent
 */
class PageBlock extends Model
{
    use HasFactory, Translatable;
    protected $table = 'pageblocks';
    protected $fillable = [
        'page_id',
        'title_ru',
        'title_en',
        'content_ru',
        'content_en',
        'orderNumber',
        'showInSidebar',
        'alias'
    ];
    public $timestamps = false;
}
