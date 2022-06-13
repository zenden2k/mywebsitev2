<?php

namespace App\Http\Resources;

use App\Helpers\StringHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogCommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $res = parent::toArray($request);
        $res['ip'] = StringHelper::ipToString($this->ip);
        return $res;
    }
}
