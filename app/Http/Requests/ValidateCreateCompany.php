<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateCreateCompany extends FormRequest
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
            'name' => 'required|unique:companies|max:100',
            'country' => 'required|max:100',
            'address' => 'required|max:100',
            'phone_number' => 'required|max:100',
            'mobile_phone' => 'required|max:100',
            'user_id' => 'required|integer',
            'account_id' => 'required|integer'
        ];
    }
}