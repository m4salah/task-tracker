<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Response;
use Validator;
use App\Models\User;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;


class AuthController extends Controller
{
    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] is_admin
     */
    public function register(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
            'is_admin' => 'required|boolean',
        ]);

        if ($validation->fails()) {
            return responseJson(0, $validation->errors()->first(), $validation->errors());
        }
        $request->merge(['password' => bcrypt($request->password)]);

        $user = User::create($request->all());

        if ($user) {
            $token = $user->createToken($user->email)->plainTextToken;
            return responseJson(1, 'User Registers succefully', [
                'user' => $user,
                'token' => $token,
                ]
            );
        }
        return responseJson(0, 'Something wrong');
    }

    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     */
    public function login(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return responseJson(0, $validator->errors()->first(), $validator->errors());
        }
        $user = User::where('email', $request->email)->first();
        if(auth()->validate($request->all())) {
            $token = $user->createToken($request->email)->plainTextToken;
            return responseJson(1, 'تم التسجيل الدخول', [
                'user' => $user,
                'api_token' => $token
            ]);
        } else {
            return responseJson(0, ' تسجيل الدخول غير صحيح');
        }
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request) {
        auth()->user()->tokens()->delete();
        return responseJson(1, 'Logout successfully');
    }
}
