<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceivingDetail extends Model
{
    protected $table = "receiving_details";
    protected $primaryKey = "id";
    protected $fillable = ['id', 'rec_id', 'catg_id', 'catg_code', 'catg_desc', 'unit_price', 'quantity', 'unit', 'dest_location_id', 'dest_location_code', 'created_by', 'updated_by'];
}
