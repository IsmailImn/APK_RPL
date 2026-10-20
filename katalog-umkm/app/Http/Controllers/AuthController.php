<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        $validator = validator($request->all(), [
            'nama'=>'required|string|max:255',
            'email'=>'required|string|max:255|unique:table_pengguna,email',
            'no_wa'=>'required|string|max:20',
            'password'=>'required|string|min:6'
        ]);

        if ($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $user = Pengguna::create([
            'nama'=>$request->nama,
            'email'=>$request->email,
            'no_wa'=>$request->no_wa,
            'password_hash'=>Hash::make($request->password),
            'peran'=> 'pengguna'
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status'=>'success',
            'message'=>'Registrasi Berhasil',
            'access_token'=>$token,
            'token_type'=>'Bearer'
        ], 201);
    }

    public function login(Request $request){
        $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);

        $user = Pengguna::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password_hash)){
            return response([
                'status'=>'error',
                'message'=>'Email atau Password anda salah'
            ], 401);
        }

        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response([
            'status'=>'success',
            'message'=>'Login Berhasil',
            'acces_token'=>$token,
            'token_type'=>'Bearer',
            'user'=>$user
        ]);
    }

    public function me(){
        return response()->json([
            'status'=>'success',
            'message'=>'Menampilkan Data profil User',
            'data'=>Auth::user()
        ]);
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status'=>'success',
            'message'=>'Berhasil logutt'
        ]);
    }
}
