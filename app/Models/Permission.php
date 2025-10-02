<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    /**
     * Column info:
     *
     * id: integer
     * key: string
     * sub_menu_id: integer
     * created_at: timestamp
     * updated_at: timestamp
     */
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "permissions";

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = "id";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'sub_menu_id',
    ];
}
