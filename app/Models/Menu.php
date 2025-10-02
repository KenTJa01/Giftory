<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = "menus";
    protected $primaryKey = "id";
    protected $fillable = ['id', 'menu_name', 'menu_url', 'flag', 'created_by', 'updated_by'];

}
