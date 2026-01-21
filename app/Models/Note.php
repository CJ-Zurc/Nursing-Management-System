<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $table = 'NOTES';
    protected $primaryKey = 'NotesID';
    public $timestamps = false;

    protected $fillable = [
        'ChartID',
        'NurseID',
        'note_title',
        'note_description',
        'note_priority',
        'time_noted',
    ];

    // Relationships
    public function chart()
    {
        return $this->belongsTo(Chart::class, 'ChartID');
    }

    public function nurse()
    {
        return $this->belongsTo(User::class, 'NurseID', 'userID');
    }
}
