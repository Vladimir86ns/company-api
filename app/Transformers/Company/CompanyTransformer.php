<?php

namespace App\Transformers\Company;


use App\Company;
use League\Fractal\TransformerAbstract;

class CompanyTransformer extends TransformerAbstract
{
    public function transform(Company $company)
    {
        return [
            'name' => $company->name,
            'address' => $company->address,
            'country' => $company->country,
            'city' => $company->city,
            'phone_number' => $company->phone_number,
            'mobile_phone' => $company->mobile_phone
        ];
    }
}
