<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MArtikel;
use DB;

class ArtikelController extends Controller
{
    public function apiGetData()
    {
        DB::beginTransaction();
    	try {
    		$data = MArtikel::paginate(10);

    		DB::commit();  
    		return response()->json([
                'data' => $data,
				'success' => true,
    			'message' => 'berhasil mengambil data'
    		]);
    	
    	} catch (\Exception $e) {
    		DB::rollback();
    		return response()->json([
    			'success' => false,
				'message' => $e->getMessage()
    		]);
    	}
    }
}
