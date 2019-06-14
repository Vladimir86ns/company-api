<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Company;

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
        'user_settings_done',
        'headquarter_company_id'
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

    /**
     * Get the companies for the blog post.
     */
    public function companies()
    {
        return $this->hasMany(Company::class, 'account_id');
    }
}
