<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpendingDetail extends Model
{
    protected $table = "expending_details";
    protected $primaryKey = "id";
    protected $fillable = ['req_id', 'catg_id', 'catg_code', 'catg_desc', 'unit_price', 'req_quantity', 'unit', 'created_by', 'updated_by'];
}
