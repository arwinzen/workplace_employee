<?php

namespace App\Http\Controllers;

use App\Http\Resources\EmployeeResource;
use App\Models\Department;
use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;
//use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use App\Http\Traits\JsonTrait;
use App\Http\Resources\UserResource;
use App\Models\Employee;
use Illuminate\Support\Facades\Gate;

class ApiController extends Controller
{
    use JsonTrait;
    //
    public function __construct() {
//        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Dashboard.
     * Requires Bearer Token in order to display the user count.
     *
     * @authenticated
     * @header Authorization Bearer {{token}}
     * @response 401 scenario="invalid token"
     */
    public function dashboard (Request $request){
        $userCount = 10001; // User::count();
        $code = 0;
        $employee = Employee::whereId(1)
            ->with(['user', 'jobHistory'])
            ->first();

        return $this->jsonResponse(
            compact('userCount', 'code', 'employee'),
            '' ,
            200);

//        return response()->json(
//            compact('userCount', 'code')
//        );
    }

    /**
     * Display Users API.
     *
     * Get all users by pagination
     * @bodyParam page int optional Page number for pagination. Example: 1
     *
     * @authenticated
     * @header Authorization Bearer {{token}}
     * @response 401 scenario="invalid token"
     */
    public function users (){
//        $users = User::where('id', '<',10)->get();
        $user = User::where('id', auth()->user()->id)->first();
        $response = Gate::inspect('update', $user);

        if($response->allowed()){
            $users = User::paginate(10);
            return $this->jsonResponse(
                UserResource::collection($users)
            );
        } else {
            echo $response->message();
        }

//        return $this->jsonResponse(
//            UserResource::collection($users)
////            new UserResource($users)
////            compact('users')
//        );
//        return $this->jsonResponse(compact(User::paginate(10)));
//        return view('dashboard.users', [
//            'users' => User::paginate(10)
//        ]);
    }

    /**
     * Display Employees API.
     *
     * Get all employees by pagination
     * @bodyParam page int optional Page number for pagination. Example: 1
     *
     * @authenticated
     * @header Authorization Bearer {{token}}
     * @response 401 scenario="invalid token"
     */
    public function getEmployee (){
        $employee = User::where('id', auth()->user()->id)->first();
        $response = Gate::inspect('update', $employee);

        if($response->allowed()){
            $employees = Employee::paginate(10);
            return $this->jsonResponse(
                EmployeeResource::collection($employees)
            );
        } else {
            echo $response->message();
        }

    }

    /**
     * Login Api.
     *
     * @bodyParam email string required User email. Example: superadmin@invoke.com
     * @bodyParam password string required User password. Example: password
     * @bodyParam user_id int optional The id of the user. Example: 9
     */
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return $this->jsonResponse(
                $validator->errors(),
                'Invalid Input Parameters',
                422
            );
//            return response()->json($validator->errors(), 422);
        }

        if (! $token = JWTAuth::attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }


    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }


}
