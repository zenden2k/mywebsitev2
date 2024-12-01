<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditCommentRequest extends FormRequest
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
            'email' => 'nullable|email:rfc,dns',
            'nickname' => 'required|max:255',
            'text' => 'required|string',
            'answer' => 'nullable',
            'pageId' => 'required|exists:\App\Models\Page,id'
        ];
    }
}
