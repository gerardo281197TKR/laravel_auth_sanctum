<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\ApiController;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) { 
            $user = Auth::user(); 

            return response()->json(array(
                'status'  => true,
                'message' => 'User authenticated successfully',
                'token'   =>  $user->createToken($request->device_name)->plainTextToken,
            ));
        } 
        else { 
            return response()->json(array(
                'status'  => false,
                'message' => 'Email or password invalid',
                'token'   => null
            ));
        } 
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
            'device_name' => 'required'
        ]);

        if($validator->fails()){
           dd($validator->errors());
        }

        $data = $request->all();
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);

        $response['token'] =  $user->createToken($request->device_name)->plainTextToken;
        $response['name'] =  $user->name;

        dd($response);
    }

    public function gerardo(Request $request){
        $user = Auth::user();
        dd($user);
    }
}
