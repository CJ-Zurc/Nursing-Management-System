<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    protected $table = 'MEDICATION';
    protected $primaryKey = 'MedID';
    public $timestamps = false;

    protected $fillable = [
        'PatientID',
        'medicine_name',
        'quantity',
        'expiry_dates',
        'statuses',
        'medicine_notes',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'PatientID');
    }

    public function schedule()
    {
        return $this->hasMany(MedicationSchedule::class, 'MedID');
    }
