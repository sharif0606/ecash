<?php

namespace App\Http\Requests\ProductList;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
        $id = encryptor('decrypt',Request::instance()->id);
        return [
            'brand' => 'required',
            'category' => 'required',
            'name' => 'required',
            'serialNo' => "required|unique:product_lists,serialNo, $id"
        ];
    }

    public function messages()
    {
        return [
            'required' => 'The :attribute field is required.',
        ];
    }
}
