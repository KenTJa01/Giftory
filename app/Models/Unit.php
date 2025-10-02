<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = "units";
    protected $primaryKey = "id";
    protected $fillable = ['id', 'unit_name', 'flag', 'created_by', 'updated_by'];

}
