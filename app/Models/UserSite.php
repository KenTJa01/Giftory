<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSite extends Model
{
    protected $table = "user_sites";
    protected $primaryKey = "id";
    protected $fillable = ['id', 'site_id', 'site_code', 'user_id', 'created_by', 'updated_by'];

}
