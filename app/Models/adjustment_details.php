<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class adjustment_details extends Model
{
    protected $table = "adjustment_details";
    protected $primaryKey = "id";
    protected $fillable = ['adj_id', 'catg_id', 'catg_code', 'catg_desc', 'adj_qty', 'stock_before_adj', 'stock_after_adj', 'unit', 'reason_code', 'location_id', 'location_code', 'created_by', 'updated_by'];
}
