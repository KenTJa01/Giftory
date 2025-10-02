<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnDetail extends Model
{
    protected $table = "return_details";
    protected $primaryKey = "id";
    protected $fillable = ['id', 'ret_id', 'catg_id', 'catg_code', 'catg_desc', 'unit_price', 'quantity', 'unit', 'location_id', 'location_code', 'created_by', 'updated_by'];
}
