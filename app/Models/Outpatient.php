<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Outpatient extends Model
{
    protected $table = 'Outpatient';
    protected $primaryKey = 'PatientID';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'PatientID',
        'return_date',
        'visit_reason',
    ];
}
