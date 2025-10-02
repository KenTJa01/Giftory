<?php

namespace App\Http\Controllers;

use App\Exceptions\CommonCustomException;
use App\Interfaces\InterfaceClass;
use App\Models\Profile;
use App\Models\ProfileMenu;
use App\Models\Site;
use App\Models\User;
use App\Models\UserSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
// use Symfony\Component\HttpKernel\Profiler\Profile as ProfilerProfile;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if (!Profile::authorize(InterfaceClass::MASTER_USER_LIST)) {
            return abort(403, 'Insufficient permission.');
        }

        $addUserAllowed = false;
        if (Profile::authorize(InterfaceClass::MASTER_USER_CREATE)) {
            $addUserAllowed = true;
        }

        $users = User::where('is_active', 1)->orWhere('is_active', 0)->orderBy('id', 'asc')->get();
        // $profile = Profile::where('flag', 1)->orWhere('flag', 2)->orderBy('profile_code', 'asc')->get();
        $site = Site::where('flag', 1)->orderBy('site_code', 'asc')->get();

        return view('master-user', [
            'users' => $users,
            'add_user_allowed' => $addUserAllowed,
            'sites' => $site
        ]);
    }

    public function getAllSite() {
        $data = Site::all();
        return $data;
    }

    public function getAllProfile() {
        $user = Auth::user();

        $superUser = Profile::where('profile_code', InterfaceClass::SUPERUSERPROFILE)->first();
        $superUserMarketing = Profile::where('profile_code', InterfaceClass::SUPERUSERMARKETINGPROFILE)->first();
        $admin = Profile::where("profile_name", "Admin")->first();
        $rmkt = Profile::where("profile_name", "Regional Marketing")->first();

        if ($user->profile_id == $admin->id || $user->profile_id == $superUserMarketing->id) {
            $data = Profile::where("id", "!=", $superUser->id)->where("flag", 1)->get();
        } else if ($user->profile_id == $rmkt->id) {
            $data = Profile::where("id", "!=", $superUser->id)->where("id", "!=", $admin->id)->where("id", "!=", $rmkt->id)->where("flag", 1)->get();
        } else {
            $data = Profile::where("flag", 1)->get();
        }
        return $data;
    }

    public function getOldDataUser(Request $request) {
        $data = User::where('id', $request->user_id)->first();
        return $data;
    }

    public function postChangePw(Request $request) {
        // return $request->all();

        $user = Auth::user();

        $validate = Validator::make($request->all(), [
            'current_pw' => ['required', 'string'],
            'new_pw' => ['required', 'string'],
            'confirm_pw' => ['required', 'string'],
        ]);

        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        // return response()->json($user->password);
        // return  Hash::check($validated['new_pw'], Auth::user()->password, []);
        // Log::debug('DEBUG', ['validate' => Hash::check($validated['current_pw'], Auth::user()->password, [])]);
        // Log::debug('DEBUG', ['validateCurPass' => $validateCurPass]);

        $validateCurPass = Hash::check($validated['current_pw'], $user->password, []);

        if (!$validateCurPass) {
            throw ValidationException::withMessages(['detail' => 'Failed to change password, Current Password is wrong!']);
        }

        if ($validated['new_pw'] != $validated['confirm_pw']) {
            throw ValidationException::withMessages(['detail' => 'Failed to change password, New Password is not the same with Confirm Password!']);
        }


        DB::beginTransaction();
        try {

            $userFind = User::find($user->id);
            $userFind->password = $validated['confirm_pw'];

            $userFind->update();

            (string) $title = 'Success';
            (string) $message = 'User request successfully submitted with username: '.$userFind->username;
            (array) $data = [
                'trx_number' => $userFind->username,
            ];
            (string) $route = route('change-password');

            DB::commit();
            return response()->json([
                'title' => $title,
                'message' => $message,
                'route' => $route,
                'data' => $data,
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::warning('Validation error when submit user request', ['userId' => $user?->id, 'userName' => $user?->name, 'errors' => $e->getMessage()]);
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new CommonCustomException('Failed to submit user request', 422, $e);
        }

    }

    public function postUserSubmitEdit(Request $request)
    {
        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'profileId' => ['required', 'integer'],
            'siteData' => ['required'],
        ]);
        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        DB::beginTransaction();
        try {

            $userSiteData = UserSite::where('user_id', $request->id_user)->get();

            $i=0;
            foreach($userSiteData as $usd){
                $userSiteData[$i++]->delete();
            }

            $siteData = $request->siteData;

            foreach($siteData as $sd) {
                $site = Site::where('id', $sd)->first();

                UserSite::firstOrCreate([
                    'site_id' => $site->id,
                    'site_code' => $site->site_code,
                    'user_id' => $request->id_user,
                ], [
                    'created_by' => $user?->id,
                    'updated_by' => $user?->id,
                ]);
            }


            $userData = User::where('id', $request->id_user)->first();

            $userData->name = $request->name;
            $userData->profile_id = $request->profileId;
            $userData->is_active = $request->isActive;
            $userData->save();

            (string) $title = 'Success';
            (string) $message = 'User request successfully submitted with username: '.$request->username;
            (array) $data = [
                'trx_number' => $request->username,
            ];
            (string) $route = route('master-user');

            DB::commit();
            return response()->json([
                'title' => $title,
                'message' => $message,
                'route' => $route,
                'data' => $data,
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::warning('Validation error when submit user request', ['userId' => $user?->id, 'userName' => $user?->name, 'errors' => $e->getMessage()]);
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new CommonCustomException('Failed to submit user request', 422, $e);
        }
    }

    public function postUserSubmit(Request $request)
    {

        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
            'name' => ['required', 'string'],
            'profileId' => ['required', 'integer'],
            'siteData' => ['required'],
        ]);


        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        $username = $validated['username'];
        $userCek = User::where('username', $username)->first();

        if (!is_null($userCek)){
            throw ValidationException::withMessages(['detail' => 'User already exist!']);
        }

        $name = $validated['name'];

        // ===== BCRYPT PASSWORD =====
        $validated['password'] = bcrypt($validated['password']);
        $password = $validated['password'];

        // ===== GET PROFILE & SITE =====
        $profileId = Profile::where('id', $validated['profileId'])->first();
        $siteData = $validated['siteData'];
        $is_active = $request->isActive;

        DB::beginTransaction();
        try {

            /** Insert transfer header */
            $userData = User::create([
                'username' => $username,
                'password' => $password,
                'name' => $name,
                'is_active' => $is_active,
                'profile_id' => $profileId->id,
                'homebase_site_id' => null,
                'homebase_site_code' => null,
                'created_by' => $user?->id,
                'updated_by' => $user?->id,
            ]);

            // $workProfileData = array();
            // parse_str($request->work_profile_id, $workProfileData);

            // echo 'data array '.$workProfileData['work_profile_id'][1];

            foreach ($siteData as $sd) {
                $site = Site::where('id', $sd)->first();

                UserSite::firstOrCreate([
                    'site_id' => $site->id,
                    'site_code' => $site->site_code,
                    'user_id' => $userData->id,
                ], [
                    'created_by' => $user?->id,
                    'updated_by' => $user?->id,
                ]);
            }

            (string) $title = 'Success';
            (string) $message = 'User request successfully submitted with username: '.$username;
            (array) $data = [
                'trx_number' => $username,
            ];
            (string) $route = route('master-user');

            DB::commit();
            return response()->json([
                'title' => $title,
                'message' => $message,
                'route' => $route,
                'data' => $data,
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::warning('Validation error when submit user request', ['userId' => $user?->id, 'userName' => $user?->name, 'errors' => $e->getMessage()]);
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new CommonCustomException('Failed to submit user request', 422, $e);
        }

    }


    public function getAllSiteEdit(Request $request) {
        // return $request->site_id;
        // $data = UserSite::where('user_id', $request->site_id)->get();
        $data = UserSite::where('user_id', $request->site_id)->get()->pluck('site_id')->toArray();
        return $data;
    }

    public function postUserResetPw(Request $request)
    {
        // $user = Auth::user();

        $newPw = 12345;
        $newPwBcrypt = bcrypt($newPw);

        DB::beginTransaction();
        try {

            $user = User::find($request->id_user);
            $user->password = $newPwBcrypt;

            $user->update();

            (string) $title = 'Success';
            (string) $message = 'User request successfully change password into: '.$newPw;
            (array) $data = [
                'trx_number' => $user->username,
            ];
            (string) $route = route('master-user');

            DB::commit();
            return response()->json([
                'title' => $title,
                'message' => $message,
                'route' => $route,
                'data' => $data,
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            Log::warning('Validation error when submit change password request', ['userId' => $user?->id, 'userName' => $user?->name, 'errors' => $e->getMessage()]);
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new CommonCustomException('Failed to submit change password request', 422, $e);
        }
    }


    public function getUserListDatatable(Request $request)
    {
        $user = Auth::user();

        /** Validate Input */
        $validate = Validator::make($request->all(), [
            'username' => ['nullable', 'string'],
            'name' => ['nullable', 'string'],
        ]);
        if ($validate->fails()) {
            throw new ValidationException($validate);
        }
        (array) $validated = $validate->validated();

        /** Prepare for parameters */
        $params = '';

        if (! is_null($validated['username'])) {
            $params .= " AND CAST(u.username AS TEXT) ILIKE '%".$validated['username']."%'";
        }

        if (! is_null($validated['name'])) {
            $params .= " AND CAST(u.name AS TEXT) ILIKE '%".$validated['name']."%'";
        }

        /** Get profile id */
        $suprUser = Profile::where('profile_code', InterfaceClass::SUPERUSERPROFILE)->first();
        $profileAdmin = Profile::where('profile_code', InterfaceClass::ADMINPROFILE)->first();
        $profileRegional = Profile::where('profile_code', InterfaceClass::REGIONALMARKETINGPROFILE)->first();

        /**
         * 1. Profile Superuser, Seluruh list user ditampilkan
         * 2. Profile Admin, Profile Superuser jangan ditampilkan dan list user berdasarkan site yang bisa diakses
         * 3. Profile Regional, Profile Superuser dan Admin jangan ditampilkan dan list user berdasarkan site yang bisa diakses
         */
        if ($user->profile_id != $suprUser->id) {
            $params .= " AND EXISTS (SELECT 1 FROM user_sites us2 WHERE us2.site_id = us.site_id AND us2.user_id = ".$user->id.")"." AND u.profile_id != ".$suprUser->id;
        }
        if ($user->profile_id == $profileRegional->id) {
            $params .= " AND u.profile_id != ".$profileAdmin->id." AND u.profile_id != ".$profileRegional->id;
        }

        $sql = ("SELECT DISTINCT u.id, u.username, u.name, u.is_active
            FROM users u, user_sites us
            WHERE u.id = us.user_id $params
            ORDER BY u.id DESC");

		$data = DB::select($sql);
        // $data = User::all();

        $canEdit = Profile::authorize(InterfaceClass::MASTER_USER_EDIT);
        $canReset = Profile::authorize(InterfaceClass::MASTER_USER_RESET);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn("actions", function($row) use ($canEdit, $canReset) {
                $buttons = '';
                // if (Profile::authorize(InterfaceClass::MASTER_USER_EDIT)) {
                if ($canEdit) {
                    $buttons = '
                    <button type="submit" class="btn btn-primary editUser" id="btnEditUser" data-u="'.$row->id.'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                        </svg>
                    </button>';
                }
                // if (Profile::authorize(InterfaceClass::MASTER_USER_RESET)) {
                if ($canReset) {
                    $buttons .= '<button type="submit" class="btn btn-danger changePw" data-id="'.$row->id.'" style="margin-left: 10px">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-key-fill" viewBox="0 0 16 16">
                            <path d="M3.5 11.5a3.5 3.5 0 1 1 3.163-5H14L15.5 8 14 9.5l-1-1-1 1-1-1-1 1-1-1-1 1H6.663a3.5 3.5 0 0 1-3.163 2zM2.5 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
                        </svg>
                    </button>';
                }

                return $buttons;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function getMenu($user_id) {
        $user = User::where('id', $user_id)->first();
        $profile = ProfileMenu::where('profile_id', $user->profile_id)->get();

        $query = DB::table('users')
                    ->join('profiles', 'users.profile_id','=','profiles.id')
                    ->join('profile_menus', 'profiles.id','=','profile_menus.profile_id')
                    ->join('sub_menus', 'profile_menus.sub_menu_id','=','sub_menus.id')
                    ->join('menus', 'sub_menus.menu_id','=','menus.id')
                    ->select('sub_menu_id', 'sub_menu_name', 'sub_menu_url', 'menu_name')
                    ->where('users.id', $user_id)->get();

        Session::forget('listMenu');
        Session::put('listMenu', $query);

        // $workProfileData = array();
        // parse_str($request->work_profile_id, $workProfileData);

        // return $query;
    }

}
