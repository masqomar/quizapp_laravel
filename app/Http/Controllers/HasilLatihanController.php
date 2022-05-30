<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DHasilLatihan;
use DB;
use JWTAuth;

class HasilLatihanController extends Controller
{
    public function apiGetData()
    {
        DB::beginTransaction();
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if ($user) {
                $data = DHasilLatihan::where('id_user', $user->id)
                        ->with('getPaket')
                        ->get();
                
                DB::commit();  
                return response()->json([
                    'data'  => $data,
                    'success' => true,
                    'messsage' => 'berhasil mengambil data'
                ]);
            }
        
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
