<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login()
    {
    	return view('auth.login');
    }

    public function postlogin(Request $request)
    {
        DB::beginTransaction();
        try {
            if (Auth::attempt($request->only('email', 'password'))) {
                if (auth()->user()->role == 'admin') {
                    return response()->json([
                        'status'    => 'berhasil',
                        'role'     => 'admin',
                        'url'       => '/dashboard',
                        'message'   => 'Login Berhasil'
                    ]);
                }
            }

            return response()->json([
                'status'    => 'gagal',
                'message'   => 'Email atau Password yang anda masukkan salah !!!'
            ]);
        
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json($e);
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function logout()
    {
    	Auth::logout();
    	return redirect('/login')->with('sukses', 'Logout berhasil');
    }
}
