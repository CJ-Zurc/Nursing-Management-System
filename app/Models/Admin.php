<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'ADMIN';
    protected $primaryKey = 'userID';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'userID',
        'admin_level',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }
}
