<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    protected $table = 'MEDICATION';
    protected $primaryKey = 'MedicationID';
    public $timestamps = false;

    protected $fillable = [
        'ChartID',
        'medicine_name',
        'dosage',
        'frequency',
        'start_date',
        'end_date',
    ];
}
