<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\User;
use App\Http\Controllers\Resources\BaseApi;
use App\Http\Controllers\Resources\ResponsePackage;

class AuthController extends BaseApi
{

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'store']]);
    }

    protected function responseToken($token) {
        return ResponsePackage::create('Successful', [
            'user' => auth('api')->user(),
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ])->response();
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->responseToken(auth('api')->refresh());
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (!$token = auth('api')->attempt($credentials)) {
            return ResponsePackage::error('Unauthorized', [], 401)
                ->response();
        }

        return $this->responseToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return  ResponsePackage::create('Successful', [
            'user' => auth('api')->user(),
        ])->response();
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();
        return ResponsePackage::create('Successfully logged out', [])
            ->response();
    }

    /**
     * Creates an User Account
     */
    public function store(Request $request) {
        $user = User::findByEmail($request->input('email'));

        if (!empty($user)) {
            return ResponsePackage::error('Email Invalid', [], BaseApi::HTTP_CONFLICT)
                ->response();
        }

        if (empty($request->input('password'))) {
            return ResponsePackage::error('Password is required', [], BaseApi::HTTP_INVALID_REQUEST)
                ->response();
        }

        if (empty($request->input('name'))) {
            return ResponsePackage::error('Name is required', [], BaseApi::HTTP_INVALID_REQUEST)
                ->response();
        }

        $user = User::create([
            'email' => $request->input('email'),
            'password' =>  Hash::make($request->input('password')),
            'name' => $request->input('name'),
        ]);

        return ResponsePackage::create('User Created', ['user' => $user])
            ->response();
    }

    /**
     * Updates an User Account
     */
    public function edit(Request $request) {
        $tokenPayload = auth('')->payload();

        if ($tokenPayload->get('id') !== $request->input('id')) {
            return ResponsePackage::error('Not allowed', [], BaseApi::HTTP_AUTH_ERROR)
                ->response();
        }

        $user = User::find($request->input('id'));

        if (empty($user)) {
            return ResponsePackage::error('Invalid User', [], BaseApi::HTTP_NOT_FOUND)
                ->response();
        }

        if ($user->email !== $request->input('email') && !empty(User::findByEmail($request->input('email')))) {
            return ResponsePackage::error('Email not valid', [], BaseApi::HTTP_INVALID_REQUEST)
                ->response();
        }

        $user->email = $request->input('email') ?: $user->email;
        $user->name = $request->input('name') ?: $user->name;
        $user->password = $request->input('password') ? Hash::make($request->input('password')) : $user->password;
        $user->save();

        return ResponsePackage::create('User Updated', ['user' => $user])
            ->response();
    }

    /**
     * Deletes an User Account
     */
    public function delete(Request $request) {
        $tokenPayload = auth('api')->payload();

        if ($tokenPayload->get('id') !== $request->input('id')) {
            return ResponsePackage::error('Not allowed', [], BaseApi::HTTP_AUTH_ERROR)
                ->response();
        }

        $user = User::find($request->input('id'));

        if (empty($user)) {
            return ResponsePackage::error('Invalid User', [], BaseApi::HTTP_NOT_FOUND)
                ->response();
        }

        $user->delete();
        return ResponsePackage::create('User Deleted', ['user' => $user])
            ->response();
    }
}
