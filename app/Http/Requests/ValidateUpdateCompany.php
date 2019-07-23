<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateUpdateCompany extends FormRequest
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
            'name' => 'min:1|unique:companies|max:100',
            'country' => 'min:3|max:100',
            'address' => 'max:100',
            'phone_number' => 'min:3|max:100',
            'mobile_phone' => 'min:3|max:100',
            'company_id' => 'required|integer',
            'account_id' => 'required|integer'
        ];
    }
}