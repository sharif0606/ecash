<?php

namespace App\Http\Requests\Stock;

use Illuminate\Foundation\Http\FormRequest;

class NewStockRequest extends FormRequest
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
    
    public function rules(){
        return [
            'batchId' => 'required',
            'productId' => 'required',
        ];
    }

    public function messages(){
        return [
            'required' => 'The :attribute field is required.',
        ];
    }
}
