<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubMenu extends Model
{
    protected $table = "sub_menus";
    protected $primaryKey = "id";
    protected $fillable = ['id', 'menu_id', 'sub_menu_name', 'sub_menu_url', 'created_by', 'updated_by'];

}
