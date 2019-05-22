<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name'
    ];

    /**
     * Get the user record associated with the account.
     */
    public function user()
    {
        return $this->hasOne('App\User');
    }
}
