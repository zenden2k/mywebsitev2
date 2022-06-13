<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditBlogPostRequest extends FormRequest
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
            'category_id' => 'required|exists:\App\Models\BlogCategory,id',
            'title_ru' => 'required|string||max:255',
            'title_en' => 'nullable|string|max:255',
            'foreword_ru' => 'string',
            'foreword_en' => 'nullable|string',
            'content_ru' => 'nullable|string',
            'content_en' => 'nullable|string',
            'enable_comments' => 'boolean',
            'status' => 'boolean',
            'alias' => 'string|max:100',
        ];
    }
}
