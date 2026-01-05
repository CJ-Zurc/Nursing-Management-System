<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Procedure extends Model
{
    protected $table = 'PROCEDURE';
    protected $primaryKey = 'ProcedureID';
    public $timestamps = false;

    protected $fillable = [
        'ChartID',
        'procedure_name',
        'procedure_date',
        'notes',
    ];
}
