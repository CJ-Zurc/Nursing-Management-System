<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VitalSign extends Model
{
    protected $table = 'VITAL_SIGN';
    protected $primaryKey = 'VitalID';
    public $timestamps = false;

    protected $fillable = [
        'ChartID',
        'temperature',
        'blood_pressure',
        'heart_rate',
        'respiratory_rate',
        'recorded_at',
    ];
}
