<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;

class LoginController extends Controller
{
    public function index(){
        return view('login');
    }

    public function authenticate(Request $request) {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $users = User::orderBy('id', 'asc')->where('username', $request['username'])->get();
        
        foreach ($users as $user){
            
            $profile = Profile::where("id", $user->profile_id)->first();

            if($user->is_active == 1 && $profile->flag == 1){
                if (Auth::attempt($credentials)) {

                    $userController = new UserController;
                    $userController->getMenu($user->id);

                    $request->session()->regenerate();
                    return redirect()->intended('/home');
                }else {
                    return back()->with('error', 'Login failed !! Username or password is incorrect !!');
                }
            }elseif ($user->is_active == 0){
                return back()->with('error', 'User not active !!');
            } else if ($profile->flag == 0){
                return back()->with('error', 'Profile not active !!');

            }
        }
        return back()->with('error', 'Login failed !! Username or password is incorrect !!');
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
