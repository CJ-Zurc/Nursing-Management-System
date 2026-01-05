<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Allergy extends Model
{
    protected $table = 'ALLERGY';
    protected $primaryKey = 'AllergyID';
    public $timestamps = false;

    protected $fillable = [
        'PatientID',
        'allergy_name',
        'severity',
        'reaction',
    ];
}
