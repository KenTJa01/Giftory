<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOpnameCatgByLocation extends Model
{
    protected $table = "stock_opname_catg_by_locations";
    protected $primaryKey = "id";
    protected $fillable = ['id', 'so_id', 'location_id', 'location_code', 'catg_id', 'catg_code', 'catg_desc', 'before_quantity', 'unit', 'created_by', 'updated_by'];
}
