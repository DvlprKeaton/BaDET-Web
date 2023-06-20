<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmergencyTeam extends Model
{
    use HasFactory;

    public $table = "emergencyteam";

    protected $fillable = [
        'team_name',
        'address',
        'contact_number',
        'status'
    ];

    public $timestamps = false;
}
