<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\User;
use Validator;
use Auth;

class AuthController extends ApiController
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'required',
            ]
        );

        if ($validator->fails()) {
            return $this->fail($validator->errors());
        }
        try {
            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $plainPassword = $request->input('password');
            $user->password = app('hash')->make($plainPassword);

            $user->save();

            return $this->success(['user' => $user, 'message' => 'User Create Successfully!']);
        } catch (\Exception $e) {
            return $this->fail(['User Registration Failed!']);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]
        );

        if ($validator->fails()) {
            return $this->fail($validator->errors());
        }

        $credentials = $request->only(['email', 'password']);

        if (!$token = Auth::attempt($credentials)) {
            return $this->unauthorized(['The login credential is not correct!']);
        }

        return $this->respondWithToken($token);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Successfully Logged Out!']);
    }
}
