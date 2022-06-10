<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'alias' => $this->alias,
            'title_ru' => $this->title_ru,
            'title_en' => $this->title_en,
            'text_ru' => $this->text_ru,
            'text_en' => $this->text_en,
            'meta_keywords_ru' => $this->meta_keywords_ru,
            'meta_keywords_en' => $this->meta_keywords_en,
            'meta_description_ru' => $this->meta_description_ru,
            'meta_description_en' => $this->meta_description_en,
            'open_graph_image_ru' => $this->open_graph_image_ru,
            'open_graph_image_en' => $this->open_graph_image_en,
            'created_at' => (string) $this->createdAt,
            'modified_at' => (string) $this->modifiedAt,
            'showComments' => (bool) $this->showComments,
            'blocks' => $this->blocks->sortBy('id')->all(),
            'tabId' => $this->tabId,
            'comments_count' => $this->comments_count ?? null
    ];
    }
}
