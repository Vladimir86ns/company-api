<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Account;
use App\User;

class Company extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'country', 'city', 'address', 'phone_number', 'mobile_phone', 'account_id', 'user_id'
    ];

    /**
     * Get the account record associated with the company.
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get the account record associated with the company.
     */
    public function user()
    {
        return $this->hasOne(User::class);
    }
}