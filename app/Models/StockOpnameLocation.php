<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOpnameLocation extends Model
{
    protected $table = "stock_opname_locations";
    protected $primaryKey = "id";
    protected $fillable = ['id', 'so_id', 'site_id', 'site_code', 'location_id', 'location_code', 'created_by', 'updated_by'];
}
