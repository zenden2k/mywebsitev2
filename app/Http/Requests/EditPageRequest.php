<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditPageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'tabId' => 'required',
            'title_ru' => 'required|max:255',
            'title_en' => 'max:255',
            'text_ru' => 'string',
            'text_en' => 'string',
            'alias' => 'present|max:64',
            'meta_keywords_ru' => 'max:1024',
            'meta_keywords_en' => 'max:1024',
            'meta_description_ru' => 'max:255',
            'meta_description_en' => 'max:255',
            'open_graph_image_ru' => 'max:255',
            'open_graph_image_en' => 'max:255',
            'showComments' => 'boolean'
        ];
    }
}
