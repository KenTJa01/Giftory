<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $table = "sites";
    protected $primaryKey = "id";
    protected $fillable = ['id', 'site_code', 'store_code', 'site_description', 'flag', 'created_by', 'updated_by'];

}
