<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilePermission extends Model
{
    use HasFactory;

    /**
     * Column info:
     *
     * id: integer
     * profile_id: integer
     * key: string
     * created_at: timestamp
     * updated_at: timestamp
     */
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "profile_permissions";

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
        'profile_id',
        'permission_id',
        'created_by',
        'updated_by',
    ];
}
