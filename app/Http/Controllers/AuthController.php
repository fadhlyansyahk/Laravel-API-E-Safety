<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $req)
    {
        //validate
        $rules = [
            'name' => 'required|string',
            'username' => 'required|string|unique:users',
            'level' => 'required|string',
            'email' => 'required|string|unique:users',
            'password' => 'required|string|min:6'
        ];
        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        //cretae new user in users table
        $user = User::create([
            'name' => $req->name,
            'username' => $req->username,
            'level' => $req->level,
            'email' => $req->email,
            'password' => Hash::make($req->password)
        ]);
        $token = $user->createToken('Personal Access Token')->plainTextToken;
        $response = ['user' => $user, 'token' => $token];
        return response()->json($response, 200);
    }

    public function login(Request $req)
    {
        // validate inputs
        $rules = [
            'username' => 'required',
            'password' => 'required|string'
        ];
        $req->validate($rules);
        $user = User::where('username', $req->username)->first();
        if ($user && Hash::check($req->password, $user->password)) {
            $token = $user->createToken('Personal Access Token')->plainTextToken;
            $response = ['user' => $user, 'token' => $token];
            return response()->json($response, 200);
        }
        $response = ['message' => 'Incorrect email or password'];
        return response()->json($response, 400);
    }

    public function checkToken(Request $req)
    {
        // validate inputs
        $rules = [
            'token' => 'required'
        ];
        $req->validate($rules);

        // error_log('heheheeeeee');
        // error_log($req->token);

        $token = PersonalAccessToken::findToken($req->token);

        if ($token) {
            $user = $token->tokenable;
            $response = ['user' => $user];
            return response()->json($response, 200);
        }

        $response = ['message' => 'Invalid Token'];
        return response()->json($response, 400);
    }
}
