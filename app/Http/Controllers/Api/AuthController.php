<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Log;
use JWTAuth;

class AuthController extends Controller {
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'refresh']]);
    }

    public function login(UserRequest $request)
    {
        $credentials = $request->only('email', 'password');
        if ($token = $this->guard()->attempt($credentials)) {
            $user = $this->guard()->user();
            return $this->respondSuccess($token, $user);
        }

        return response()->json([
            'errors' => '1',
            'msg' => 'login failed',
        ]);
    }

    public function register(UserRequest $request)
    {
        $data = $request->only('email', 'password', 'name');
        $exist = User::where('email', $data['email'])->first();
        if ($exist) {
            return response()->json([
                'errors' => ['邮箱已注册'],
            ]);
        }
        $user = User::create($data);
        $token = $this->guard()->login($user);
        $user = $this->guard()->user();

        return $this->respondSuccess($token, $user);
    }

    public function me()
    {
        $user = $this->guard()->user();
        return response()->json([
            'errors' => 0,
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar,
        ]);
    }

    public function logout()
    {
        $this->guard()->logout();

        return response()->json([
            'errors' => 0,
        ]);
    }

    public function refresh()
    {
        return $this->respondSuccess($this->guard()->refresh(), $this->guard()->user());
    }

    protected function respondSuccess($token, $user)
    {
        return response()->json([
            'errors' => 0,
            'token' => $token,
            'expires_in' => $this->guard()->factory()->getTTL() * 60,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar,
            ],
        ]);
    }

    public function guard()
    {
        return Auth::guard('api');
    }
}