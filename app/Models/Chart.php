<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chart extends Model
{
    protected $table = 'CHART';
    protected $primaryKey = 'ChartID';
    public $timestamps = false;

    protected $fillable = [
        'PatientID',
        'NurseID',
        'date_created',
    ];
}
