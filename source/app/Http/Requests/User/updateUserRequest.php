<?php

namespace App\Http\Requests\User;
use Illuminate\Http\Request;

use Illuminate\Foundation\Http\FormRequest;

class updateUserRequest extends FormRequest
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
            'role' => 'required|integer',
            'fullName' => 'required|string',
            'mobileNumber' => 'required|numeric|unique:users,mobileNumber,'.$id,
        ];
    }

    public function messages()
    {
        return [
            'required' => 'The :attribute field is required.',
        ];
    }
}
