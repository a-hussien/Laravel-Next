<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password'))
        ];

        try
        {
            $user = User::create($data);
            $response = [
                'msg' => 'User Created',
                'data' => $user
            ];
        }
        catch (\Throwable $th) {
            $response = [
                'msg' => 'Error',
                'data' => $th
            ];
        }

        return response()->json($response);
    }

    public function show(User $user)
    {
        $user = Auth::user();

        if($user) {
            return response()->json(['msg' => 'Authenticated user', 'data' => $user], 200);
        }
    }

    public function signin(Request $request)
    {
        try
        {
            $checkAuth = Auth::attempt($request->only('email', 'password'));

            if(!$checkAuth)
            {
                return response()->json(['msg' => 'Invalid credentials'], 401);
            }

            $user = Auth::user();
            $token = $user->createToken('token')->plainTextToken;
            $cookie = Cookie('jwt', $token, 60 * 24);

            return response()->json(['msg' => 'Authenticated', 'data' => $user], 200)
                             ->withCookie($cookie);


        }
        catch (\Throwable $th) {
            return response()->json(['msg' => 'error', 'data' => $th], 400);
        }
    }

    public function signout(User $user)
    {
        $user = Auth::user();

        try {

            if($user)
            {
                $cookie = Cookie::forget('jwt');
                return response()->json(['msg' => 'User logout success'], 200)
                                 ->withCookie($cookie);
            }
        } catch (\Throwable $th) {
            return response()->json(['msg' => 'Error', 'data' => $th], 400);
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
