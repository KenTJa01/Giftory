<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $table = "locations";
    protected $primaryKey = "id";
    protected $fillable = ['location_code', 'location_name', 'flag', 'created_by', 'updated_by'];

}
