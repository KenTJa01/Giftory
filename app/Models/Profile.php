<?php

namespace App\Models;

use App\Interfaces\InterfaceClass;
use App\Models\Permission;
// use App\Models\Profile;
use App\Models\ProfilePermission;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Profile extends Model
{
    /**
     * Column info:
     *
     * id: integer
     * profile_code: string
     * profile_name: string
     * flag: integer
     * created_at: timestamp
     * updated_at: timestamp
     * created_by: integer
     * updated_by: integer
     */

     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "profiles";

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
        'profile_code',
        'profile_name',
        'flag',
        'created_by',
        'updated_by',
    ];

    /** Function for authorize user permissions */
    public static function authorize($permission)
    {
        $user = Auth::user();
        $superUserProfileId = Profile::where('profile_code', InterfaceClass::SUPERUSERPROFILE)->first()->id;
        $profilePermissions = ProfilePermission::where('profile_id', $user?->profile_id)->get()->pluck('permission_id')->toArray();
        $permissionId = Permission::where('key', $permission)->first()->id;

        /** Check profile is super user or not */
        if ($user?->profile_id == $superUserProfileId) {
            return true;
        }

        /** Check permission */
        if (!in_array($permissionId, $profilePermissions)) {
            return false;
        }
        return true;
    }
}
