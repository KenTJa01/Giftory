<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpendingHeader extends Model
{
    protected $table = "expending_headers";
    protected $primaryKey = "id";
    protected $fillable = ['id', 'req_no', 'req_date', 'origin_site_id', 'origin_site_code', 'location_id', 'location_code', 'approved_by', 'approved_date', 'flag', 'created_by', 'updated_by', 'note'];
}
