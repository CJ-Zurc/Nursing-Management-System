<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inpatient extends Model
{
    protected $table = 'Inpatient';
    protected $primaryKey = 'PatientID';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'PatientID',
        'WardID',
        'roomNumber',
        'bedNumber',
        'admission_date',
        'discharge_date',
        'attending_physician',
    ];
}
