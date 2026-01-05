<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $table = 'PATIENT';
    protected $primaryKey = 'PatientID';
    public $timestamps = false;

    protected $fillable = [
        'WardID',
        'first_name',
        'last_name',
        'dateofbirth',
        'Sex',
        'contact_Number',
        'Guardian',
        'guardian_Number',
        'adress',
        'height',
        'weight',
        'blood_type',
        'patientType',
    ];

    // Relationships (future use)
    public function ward()
    {
        return $this->belongsTo(Ward::class, 'WardID');
    }
}
