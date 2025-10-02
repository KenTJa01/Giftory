<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = "suppliers";
    protected $primaryKey = "id";
    protected $fillable = ['id', 'supp_code', 'supp_name', 'flag', 'created_by', 'updated_by'];

}
