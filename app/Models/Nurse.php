<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nurse extends Model
{
    protected $table = 'NURSE';
    protected $primaryKey = 'userID';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'userID',
        'WardID',
        'license_number',
        'role',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }
}
