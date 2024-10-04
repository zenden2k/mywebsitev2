<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditMenuItemRequest extends FormRequest
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
            'title_ru' => 'string|max:255',
            'title_en' => 'string|max:255',
            'url_ru' => 'nullable|string',
            'url_en' => 'nullable|string',
            'target_page_id' => 'nullable|exists:\App\Models\Page,id',
            'tab_id' => 'required|exists:\App\Models\Tab,id',
            'order_number' => 'int',
            'status' => 'boolean'
        ];
    }
}
