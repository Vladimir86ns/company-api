<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'employee_company_id',
        'hire_date',
        'user_info_id',
        'company_id'
    ];

    /**
     * Get the company.
     */
    public function companies()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the user info.
     */
    public function userInfo()
    {
        return $this->belongsTo(UserInfo::class);
    }
}
