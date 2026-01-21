<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VitalSign extends Model
{
    protected $table = 'VITAL_SIGN';
    protected $primaryKey = 'VitalID';
    public $timestamps = false;

    protected $fillable = [
        'PatientID',
        'vital_type',
        'value',
        'unit',
        'time_taken',
        'SystolicBP',
        'DiastolicBP',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'PatientID');
    }
