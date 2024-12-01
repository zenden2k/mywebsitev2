<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditBlogCommentRequest extends FormRequest
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
            'blog_post_id' => 'required|exists:\App\Models\BlogPost,id'
        ];
    }
}
