<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditTabRequest extends FormRequest
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
            'title_ru' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'url' => 'required|string',
            'orderNumber' => 'required|int',
            'alias' => 'required|string|max:40',
            'active' => 'required|boolean'
        ];
    }
}
