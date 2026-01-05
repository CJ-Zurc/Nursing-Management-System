<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    protected $table = 'WARD';
    protected $primaryKey = 'WardID';
    public $timestamps = false;

    protected $fillable = [
        'WardName',
        'nRooms',
        'occupiedRooms',
        'aRooms',
    ];
}
