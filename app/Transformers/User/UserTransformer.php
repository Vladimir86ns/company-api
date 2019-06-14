<?php

namespace App\Transformers\User;


use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        return [
            'email' => $user->email,
            'first_name' => $user->userInfo->first_name,
            'last_name' => $user->userInfo->last_name,
            'country' => $user->userInfo->country,
            'city' => $user->userInfo->city,
            'address' => $user->userInfo->address,
            'phone_number' => $user->userInfo->phone_number,
            'mobile_phone' => $user->userInfo->mobile_phone,
            'company_settings_done' => $user->account->company_settings_done,
            'user_settings_done' => $user->account->user_settings_done
        ];
    }
}
