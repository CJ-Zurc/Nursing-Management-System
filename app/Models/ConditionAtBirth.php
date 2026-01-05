<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConditionAtBirth extends Model
{
    protected $table = 'CONDITION_AT_BIRTH';
    protected $primaryKey = 'ConditionID';
    public $timestamps = false;

    protected $fillable = [
        'PatientID',
        'condition_name',
        'description',
    ];
}
