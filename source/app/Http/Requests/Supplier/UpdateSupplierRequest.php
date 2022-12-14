<?php

namespace App\Http\Requests\Supplier;
use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierRequest extends FormRequest
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
            'supCode' 	=> "required|string",
            'name' 		=> 'required|string'
        ];
    }

    public function messages()
    {
        return [
            'unique' => 'Supplier already exist',
            'required' => 'The :attribute field is required.',
        ];
    }
}
