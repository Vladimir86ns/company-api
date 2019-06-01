<?php

namespace App\Services\User;

use App\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class UserService
{
    /**
     * Show the application dashboard.
     *
     * @param string $id
     * @return User;
     */
    public function getUserById(string $id)
    {
        return User::where('id', $id)->first();
    }

    /**
     * Update user.
     *
     * @param User $user.
     * @param array $attributes
     * @return User;
     */
    public function updateUser(User $user, array $attributes)
    {
        DB::transaction(function () use ($user, $attributes) {
            $user->userInfo->update($attributes);
            $user->update($attributes);
            $user->account->update(['user_settings_done' => 1]);
        });

        return $user;
    }
}
