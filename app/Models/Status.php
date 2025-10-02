<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = "status";
    protected $primaryKey = "id";
    protected $fillable = ['id', 'module', 'flag_desc', 'flag_value', 'created_by', 'updated_by'];

}
