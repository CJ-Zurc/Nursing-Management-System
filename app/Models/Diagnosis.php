<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
    protected $table = 'DIAGNOSIS';
    protected $primaryKey = 'DiagnosisID';
    public $timestamps = false;

    protected $fillable = [
        'ChartID',
        'diagnosis_name',
        'diagnosis_date',
        'notes',
    ];
}
