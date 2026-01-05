<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'INVENTORY';
    protected $primaryKey = 'InventoryID';
    public $timestamps = false;

    protected $fillable = [
        'PatientID',
        'item_name',
        'quantity',
        'unit',
        'last_updated',
    ];
}
