<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnHeader extends Model
{
    protected $table = "return_headers";
    protected $primaryKey = "id";
    protected $fillable = ['id', 'ret_no', 'ret_date', 'location_id', 'location_code', 'site_id', 'site_code', 'supp_code', 'supp_name', 'flag', 'created_by', 'updated_by', 'note'];
}
