<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ValidateUpdateEmployee extends FormRequest
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
            'id' => 'required|integer',
            'first_name' => 'min:3|max:100',
            'last_name' => 'min:3|max:100',
            'email' => [
                'email',
                'max:100',
                Rule::unique('employees')->where(function ($query) {
                    $query->where('company_id', $this->attributes['company_id'])
                        ->where('id', '!=', $this->attributes['id']);
                })
            ],
            'country' => 'max:100',
            'city' => 'max:100',
            'address' => 'min:3|max:100',
            'employee_company_id' => [
                'min:3',
                'max:100',
                Rule::unique('employees')->where(function ($query) {
                    $query->where('employee_company_id', $this->attributes['employee_company_id'])
                        ->where('id', '!=', $this->attributes['id']);
                })
            ],
            'hire_date' => 'date',
            'phone_number' => 'max:20',
            'mobile_phone' => 'max:20',
            'company_id' => 'required|integer',
            'account_id' => 'required|integer'
        ];
    }
}
