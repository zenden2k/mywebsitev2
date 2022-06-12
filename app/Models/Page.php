<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Page
 *
 * @property int $id
 * @property string $alias
 * @property string $text_ru
 * @property string|null $text_en
 * @property string $createdAt
 * @property string|null $modifiedAt
 * @property string $title_ru
 * @property string|null $title_en
 * @property string|null $meta_keywords_ru
 * @property string|null $meta_keywords_en
 * @property string|null $meta_description_ru
 * @property string|null $meta_description_en
 * @property string|null $open_graph_image_ru
 * @property string|null $open_graph_image_en
 * @property int|null $tabId
 * @property int $showComments
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PageBlock[] $blocks
 * @property-read int|null $blocks_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SidebarBlock[] $sidebarBlocks
 * @property-read int|null $sidebar_blocks_count
 * @method static \Illuminate\Database\Eloquent\Builder|Page newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Page newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Page query()
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereMetaDescriptionEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereMetaDescriptionRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereMetaKeywordsEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereMetaKeywordsRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereModifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereOpenGraphImageEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereOpenGraphImageRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereShowComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereTabId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereTextEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereTextRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereTitleEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereTitleRu($value)
 * @mixin \Eloquent
 */
class Page extends AbstractModel
{
    use HasFactory, SoftDeletes;

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'modifiedAt';

    protected $fillable = [
        'title_ru',
        'title_en',
        'text_ru',
        'text_en',
        'alias',
        'meta_keywords_ru',
        'meta_keywords_en',
        'meta_description_ru',
        'meta_description_en',
        'open_graph_image_ru',
        'open_graph_image_en',
        'tabId',
        'showComments'
    ];


    protected $attributes = [
        'showComments' => false,
        'text_ru' => '',
        'text_en' => '',
    ];

    public function blocks()
    {
        return $this->hasMany(PageBlock::class, 'page_id', 'id');
    }

    public function sidebarBlocks()
    {
        return $this->belongsToMany(SidebarBlock::class, 'page_sidebarblocks', 'pageId', 'sidebarBlockId');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'pageId', 'id');
    }

    public function saveBlocks($blocks)
    {
        $blockObjects = [];
        foreach ($blocks as $block) {
            unset($block['id']);
            $blockObj = new PageBlock($block);
            $blockObj->page_id = $this->id;
            $blockObjects[] = $blockObj;
        }
        $this->blocks()->delete();
        $this->blocks()->saveMany($blockObjects);
    }

}
