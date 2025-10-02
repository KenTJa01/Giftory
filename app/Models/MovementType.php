<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovementType extends Model
{
    protected $table = "movement_types";
    protected $primaryKey = "id";
    protected $fillable = ['id', 'mov_code', 'mov_name', 'created_by', 'updated_by'];

}
