<?php


namespace App\Helpers;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use JetBrains\PhpStorm\ArrayShape;

class ArrayHelper
{
    public static function keysCreated($new, $old)
    {
        return array_keys(array_diff_key($new, $old));
    }

    public static function keysDeleted($new, $old)
    {
        return array_keys(array_diff_key($old, $new));
    }

    public static function keysUpdated($new, $old)
    {
        return array_diff(
            array_keys(array_diff_assoc($new, $old)),
            static::keysCreated($new, $old)
        );
    }

    /**
     * Подготавливает пагинацию resource collection для отдачи в json
     * Это нужно, потому что Laravel теряет мета-информацию при вложении пагинатора в массив
     * @param ResourceCollection $resourceCollection
     * @return array
     * @throws \InvalidArgumentException
     */
    #[ArrayShape(['data' => ResourceCollection::class, 'links' => "array", 'meta' => "array"])]
    public static function prepareCollectionPagination(ResourceCollection $resourceCollection): array
    {
        if (!$resourceCollection->resource instanceof LengthAwarePaginator) {
            throw new \InvalidArgumentException();
        }
        $arr = $resourceCollection->resource->toArray();
        $arr['data'] = $resourceCollection;
        return $arr;
    }
}
