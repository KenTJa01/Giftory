<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = "stocks";
    protected $primaryKey = "id";
    protected $fillable = ['id', 'site_id', 'site_code', 'location_id', 'location_code', 'catg_id', 'catg_code', 'quantity', 'unit', 'avg_cost', 'so_flag', 'created_by', 'updated_by'];

}
