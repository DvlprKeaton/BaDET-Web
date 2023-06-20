<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cases extends Model
{
    use HasFactory;

    public $table = "cases";

    protected $fillable = [
        'case_name',
        'latitude',
        'longitude',
        'description',
        'status',
        'created_by',
        'updated_by'
    ];

}
