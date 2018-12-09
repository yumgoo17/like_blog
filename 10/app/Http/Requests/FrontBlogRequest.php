<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FrontBlogRequest extends FormRequest
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
            'year'        => 'integer',
            'month'       => 'integer',
            'category_id' => 'integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'year.integer'        => '年は整数にしてください',
            'month.integer'       => '月は整数にしてください',
            'category_id.integer' => 'カテゴリーIDは整数にしてください',
            'category_id.min'     => 'カテゴリーIDは1以上にしてください',
        ];
    }
}
