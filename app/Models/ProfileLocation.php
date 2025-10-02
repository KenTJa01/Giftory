<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileLocation extends Model
{
    protected $table = "profile_locations";
    protected $primaryKey = "id";
    protected $fillable = ['id', 'profile_id', 'location_id', 'created_by', 'updated_by'];

}
