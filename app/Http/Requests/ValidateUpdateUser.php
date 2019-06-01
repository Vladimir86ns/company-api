<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateUpdateUser extends FormRequest
{
    public $attributes;

    /**
     * Create a new controller instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes) {
        $this->attributes = $attributes;
    }

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
            'first_name' => 'required|min:3|max:100',
            'last_name' => 'required|min:3|max:100',
            'email' => 'required|unique:users,email,' . $this->attributes['user_id'],
            'country' => 'required|max:100',
            'city' => 'required|max:100',
            'address' => 'max:100',
            'phone_number' => 'max:20',
            'mobile_phone' => 'max:20',
            'user_id' => 'required|integer',
            'account_id' => 'required|integer'
        ];
    }
}
