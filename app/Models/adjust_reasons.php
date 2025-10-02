<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class adjust_reasons extends Model
{
    protected $table = "adjust_reasons";
    protected $primaryKey = "id";
    protected $fillable = ['id', 'reason_code', 'reason_desc', 'default_operator', 'created_by', 'updated_by'];
}
