<?php

namespace App\Http\Requests\Appointment;

use Illuminate\Foundation\Http\FormRequest;

class NewAppointmentRequest extends FormRequest
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
            'attName' 	=> 'required|string',
            'name' 		=> 'required|string',
            'age' 		=> 'required|string',
            'gender' 	=> 'required|string',
            'contact' 	=> 'required|string',
            'appDate' 	=> 'required|string',
            'appTime' 	=> 'required|string',
            'drName' 	=> 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'The :attribute field is required.',
        ];
    }
}
