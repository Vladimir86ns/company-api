<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'country',
        'city',
        'address',
        'phone_number',
        'mobile_phone',
        'user_id'
    ];

    /**
     * Get the user record associated with the user info.
     */
    public function user()
    {
        return $this->hasOne('App\User');
    }

    /**
     * Get the employee record.
     */
    public function employee()
    {
        return $this->hasOne(Employee::class);
    }
}
