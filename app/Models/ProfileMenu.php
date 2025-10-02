<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileMenu extends Model
{
    protected $table = "profile_menus";
    protected $primaryKey = "id";
    protected $fillable = ['profile_id', 'sub_menu_id', 'created_by', 'updated_by'];

}
