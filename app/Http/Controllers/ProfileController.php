<?php

namespace App\Http\Controllers;

use App\Exceptions\CommonCustomException;
use App\Interfaces\InterfaceClass;
use App\Models\Location;
use App\Models\Profile;
use App\Models\ProfileLocation;
use App\Models\ProfileMenu;
use App\Models\ProfilePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Mockery\Undefined;
use Yajra\DataTables\Facades\DataTables;

class ProfileController extends Controller
{
    public function index()
    {
        /** Validate permissions */
        if (!Profile::authorize(InterfaceClass::MASTER_PROFILE_LIST)) {
            return abort(403, 'Insufficient permission.');
        }

        $isReqProfAllowed = false;
        if (Profile::authorize(InterfaceClass::MASTER_PROFILE_CREATE)) {
            $isReqProfAllowed = true;
        }

        $profiles = Profile::all();
        $menus = DB::table('menus')->get();
        $submenus = DB::table('sub_menus')->get();
        $permissions = DB::table('permissions')->get();
        $profilePermissions = DB::table('profile_permissions')->get();
        return view('master-profile', [
            'menus' => $menus,
            'submenus' => $submenus,
            'permissions' => $permissions,
            'is_req_prof_allowed' => $isReqProfAllowed,
        ]);
    }

    public function getProfileListDatatable(Request $request){
        $user = Auth::user();
        $canEdit = Profile::authorize(InterfaceClass::MASTER_USER_EDIT);
        $suprUser = Profile::where('profile_code', InterfaceClass::SUPERUSERPROFILE)->first();
        $params = '';

        if ($user->profile_id != $suprUser->id) {
            $params .= "WHERE id != ".$suprUser->id;
        }

        $sql = ("SELECT * FROM profiles $params ORDER BY id ASC");
        $data = DB::select($sql);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn("actions", function($row) use($canEdit) {
                $buttons = '';
                if ($canEdit) {
                    $buttons = '
                    <button type="submit" class="btn btn-primary editProfile" id="btnEditProfile" data-p="'.$row->id.'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                        </svg>
                    </button>';
                }
                return $buttons;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function getOldDataProfileEdit(Request $request){
        $profile = DB::table('profiles')->where('id', $request->profile_id)->get();
        $proLoc = DB::table('profile_locations')->where('profile_id', $request->profile_id)->get();
        $profMenu = DB::table('profile_menus')->where('profile_id', $request->profile_id)->get();
        $profPer = DB::table('profile_permissions')->where('profile_id', $request->profile_id)->get();

        $menu = DB::table('menus')->get();
        $submenu = DB::table('sub_menus')->get();
        $profSubmenu = DB::table('sub_menus')
                        ->join('profile_menus', 'profile_menus.sub_menu_id', '=', 'sub_menus.id')
                        ->where('profile_menus.profile_id', $request->profile_id)
                        ->select('sub_menus.menu_id')
                        ->get();

        return response()->json([
            'profile' => $profile,
            'profileLocation' => $proLoc,
            'profileMenu' => $profMenu,
            'profilePermission' => $profPer,
            'menu' => $menu,
            'submenu' => $submenu,
            'profSubmenu' => $profSubmenu
        ]);
    }

    public function getLocationById(Request $request)
    {
        $data = ProfileLocation::where('profile_id', $request->profileId)->get()->pluck('location_id')->toArray();
        return $data;
    }

    public function getProfileLocation()
    {
        $data = Location::all();
        return $data;
    }

    public function postProfileReqSubmit(Request $request){
        // return response()->json($request);

        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            'profile_code' => ['required', 'string', 'max:4'],
            'profile_name' => ['required', 'string'],
            'location_id' => ['nullable', 'array'],
            'flag' => ['required', 'integer'],
            // 'submenu' => ['required', 'array'],
            'profile_permission' => ['required', 'array'],
        ]);

        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        $code = Str::upper($request['profile_code']);

        $profileCode = Profile::where('profile_code', $code)->first();
        // Check Profile Code
        if($profileCode != null){
            throw ValidationException::withMessages(['detail' => 'Profile Code Already Exists']);
        }

        (array) $location = $validated['location_id'];
        // (array) $profileMenu = $validated['submenu'];
        (array) $profilePermission = $validated['profile_permission'];

        DB::beginTransaction();
        try {
            // Insert Profile
            $profID = Profile::firstOrCreate([
                'profile_code' => $code,
            ], [
                'profile_name' => $validated['profile_name'],
                'flag' => $validated['flag'],
                'created_by' => $user?->id,
                'updated_by' => $user?->id,
            ]);

            // Insert Profile Location
            if ($location != null){
                foreach($location as $loc){
                    ProfileLocation::firstOrCreate([
                        'profile_id' => $profID->id,
                        'location_id' => $loc,
                    ], [
                        'created_by' => $user?->id,
                        'updated_by' => $user?->id,
                    ]);
                }
            }

            //Insert Profile Permission
            foreach($profilePermission as $pp){
                ProfilePermission::firstOrCreate([
                    'profile_id' => $profID->id,
                    'permission_id' => $pp,
                ], [
                    'created_by' => $user?->id,
                    'updated_by' => $user?->id,
                ]);

                // Get sub_menu_id in table permissions
                $perSubId = DB::table('profile_permissions')
                    ->join('permissions', 'permissions.id', '=', 'profile_permissions.permission_id')
                    ->where('profile_permissions.permission_id', '=', $pp)
                    ->select('permissions.id','permissions.sub_menu_id')
                    ->get();

                // Check profile menu
                $profileMenu = ProfileMenu::where('profile_id', $profID->id)->where('sub_menu_id', $perSubId->first()->sub_menu_id)->first();
                // return response()->json($profileMenu->first());

                if ($profileMenu == null){
                    ProfileMenu::firstOrCreate([
                        'profile_id' => $profID->id,
                        'sub_menu_id' => $perSubId->first()->sub_menu_id,
                    ], [
                        'created_by' => $user?->id,
                        'updated_by' => $user?->id,
                    ]);
                }
            }

            (string) $title = 'Success';
            (string) $message = 'Profile request successfully submitted';
            (string) $route = route('master-profile');

            DB::commit();
            return response()->json([
                'title' => $title,
                'message' => $message,
                'route' => $route,
                'data' => null,
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::warning('Validation error when submit profile request', ['userId' => $user?->id, 'userName' => $user?->name, 'errors' => $e->getMessage()]);
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new CommonCustomException($e->getMessage(), 422, $e);
        }
    }

    public function postProfileUpdateSubmit(Request $request){
        // return response()->json($request);

        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            'profile_id' => ['required', 'integer'],
            'profile_code' => ['required', 'string', 'max:4'],
            'profile_name' => ['required', 'string'],
            'location_id' => ['nullable', 'array'],
            'flag' => ['required', 'integer'],
            // 'submenu' => ['required', 'array'],
            'profile_permission' => ['required', 'array'],
        ]);

        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();


        // Huruf Kapital
        $code = Str::upper($validated['profile_code']);

        (array) $location = $validated['location_id'];
        // (array) $profileMenu = $validated['submenu'];
        (array) $profilePermission = $validated['profile_permission'];

        // return response()->json($validated['flag']);

        DB::beginTransaction();
        try {
            $profile = Profile::find($validated['profile_id']);
            $profile->profile_code = $code;
            $profile->profile_name = $validated['profile_name'];
            $profile->flag = $validated['flag'];
            $profile->updated_by = $user->id;
            $profile->update();

            // Delete Profile Location
            $profileLocation = ProfileLocation::where('profile_id', $validated['profile_id'])->get();
            $j=0;
            if ($profileLocation != null){
                foreach ($profileLocation as $pl){
                    $profileLocation[$j++]->delete();
                }
            }

            if ($location != null){
                // Insert Profile Location
                foreach($location as $loc){
                    ProfileLocation::firstOrCreate([
                        'profile_id' => $validated['profile_id'],
                        'location_id' => $loc,
                    ], [
                        'created_by' => $user?->id,
                        'updated_by' => $user?->id,
                    ]);
                }
            }

            // Delete Profile Permission Prev
            $profilePermissionPrev = ProfilePermission::where('profile_id', $validated['profile_id'])->get();
            $i=0;
            if ($profilePermissionPrev != null){
                foreach ($profilePermissionPrev as $ppp){
                    $profilePermissionPrev[$i++]->delete();
                }
            }

            // Delete Profile Menu Prev
            $profileMenuPrev = ProfileMenu::where('profile_id', $validated['profile_id'])->get();
            $i=0;
            if ($profileMenuPrev != null){
                foreach ($profileMenuPrev as $pmp){
                    $profileMenuPrev[$i++]->delete();
                }
            }

            //Insert Profile Permission
            foreach($profilePermission as $pp){
                ProfilePermission::firstOrCreate([
                    'profile_id' => $validated['profile_id'],
                    'permission_id' => $pp,
                ], [
                    'created_by' => $user?->id,
                    'updated_by' => $user?->id,
                ]);

                // Get sub_menu_id in table permissions
                $perSubId = DB::table('profile_permissions')
                    ->join('permissions', 'permissions.id', '=', 'profile_permissions.permission_id')
                    ->where('profile_permissions.permission_id', '=', $pp)
                    ->select('permissions.id','permissions.sub_menu_id')
                    ->get();

                // Check profile menu
                $profileMenu = ProfileMenu::where('profile_id', $validated['profile_id'])->where('sub_menu_id', $perSubId->first()->sub_menu_id)->first();
                // return response()->json($profileMenu->first());

                if ($profileMenu == null){
                    ProfileMenu::firstOrCreate([
                        'profile_id' => $validated['profile_id'],
                        'sub_menu_id' => $perSubId->first()->sub_menu_id,
                    ], [
                        'created_by' => $user?->id,
                        'updated_by' => $user?->id,
                    ]);
                }
            }

            (string) $title = 'Success';
            (string) $message = 'Profile request successfully updated';
            (string) $route = route('master-profile');

            DB::commit();
            return response()->json([
                'title' => $title,
                'message' => $message,
                'route' => $route,
                'data' => null,
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::warning('Validation error when update profile request', ['userId' => $user?->id, 'userName' => $user?->name, 'errors' => $e->getMessage()]);
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new CommonCustomException($e->getMessage(), 422, $e);
        }
    }
}
