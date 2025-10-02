<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpendingDetailRealization extends Model
{
    protected $table = "expending_detail_realizations";
    protected $primaryKey = "id";
    protected $fillable = ['id', 'req_id', 'catg_id', 'catg_code', 'catg_desc', 'unit_price', 'out_quantity', 'from_location_id', 'from_location_code', 'unit', 'created_by', 'updated_by'];
}
