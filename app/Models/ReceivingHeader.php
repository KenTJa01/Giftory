<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceivingHeader extends Model
{
    protected $table = "receiving_headers";
    protected $primaryKey = "id";
    protected $fillable = ['id', 'rec_no', 'rec_date', 'origin_site_id', 'origin_site_code', 'destination_site_id', 'destination_site_code', 'supp_code', 'supp_name', 'flag', 'created_by', 'updated_by'];

}
