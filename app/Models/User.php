<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'USER';
    protected $primaryKey = 'userID';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'userID',
        'first_name',
        'last_name',
        'contact_number',
        'email',
        'password',
        'created_at',
        'system_role',
    ];

    protected $hidden = [
        'password',
    ];

    // Relationships (optional but recommended)
    public function nurse()
    {
        return $this->hasOne(Nurse::class, 'userID', 'userID');
    }

    public function admin()
    {
        return $this->hasOne(Admin::class, 'userID', 'userID');
    }
}
