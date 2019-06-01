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
        'name',
        'company_settings_done',
        'user_settings_done'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'company_settings_done' => 'boolean',
        'user_settings_done' => 'boolean',
    ];

    /**
     * Get the user record associated with the account.
     */
    public function user()
    {
        return $this->hasOne('App\User');
    }
}
