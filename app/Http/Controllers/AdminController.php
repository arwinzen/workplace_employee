<?php

namespace App\Http\Controllers;

use App\Models\EmployeeJob;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminController extends Controller
{
    public function login (Request $request){
        if ($request->isMethod('post')){
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            if (Auth::attempt($credentials)){
                if(Auth::user()->role == 1){
                    $request->session()->regenerate();
                    $jwt_token = JWTAuth::attempt($credentials);
                    session(['jwt_token' => $jwt_token]);
                    return redirect()->route('admin.dashboard');
                }
            } else {
                return back()->withErrors([
                    'email' => 'The provided credentials do not match our records'
                ]);
            }
        }
        return view('auth.login');
    }

    public function dashboard (){
        $userCount = User::count();
        $jobCount = EmployeeJob::count();
        $deptCount = Department::count();

        $jwt_token = session('jwt_token');

        return view('dashboard', compact('jwt_token', 'userCount', 'jobCount', 'deptCount'));

    }

    public function display($group){
        switch($group)
        {
            case 'users':
                return $this->displayUsers();
                break;
            case 'jobs':
                return $this->displayJobs();
                break;
            case 'departments':
                return $this->displayDepts();
                break;
            default:
                return 0;
        }
    }

    public function displayUsers (){
        return view('dashboard.users', [
            'users' => User::paginate(10)
        ]);
    }

    public function displayJobs (){
        return view('dashboard.jobs', [
            'jobs' => Job::paginate(10)
        ]);
    }

    public function displayDepts (){
        return view('dashboard.departments', [
            'jobs' => Department::paginate(10)
        ]);
    }

    public function editUsers (Request $request){
        $user = User::whereId($request->id)->first();
        $status = "";

        if(isset($request->name) || isset($request->email)){
            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();
            $status = "Record $user->id updated";
            return redirect('admin/dashboard/user/edit/' . $user->id)
                ->with('status', $status);
        }

        return view('dashboard.edit', [
                "user" => $user
            ]
        )->with("status", $status);
    }

    public function delete (Request $request){
        $user = User::whereId($request->id)->first();
        $status = "";

        if(isset($request->id)){
            $user->delete();
            $status = "Record $user->id deleted";
            return redirect('admin/dashboard/users')
                ->with('status', $status);
        }
    }
}
