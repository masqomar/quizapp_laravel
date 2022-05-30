<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MPaket;
use App\User;
use Carbon\Carbon;
use DataTables;
use DB;
use Illuminate\Http\Request;

class PaketController extends Controller
{
    public function index()
    {
        return view('paket.index'); 
    }

    public function getData(Request $request)
    {
    	$data = MPaket::all();

    	return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('pk_time', function($data) {
                $data->jam = Carbon::createFromFormat('H:i:s', $data->pk_time)->format('H');
                $data->menit = Carbon::createFromFormat('H:i:s', $data->pk_time)->format('i');

            	if ($data->jam == '00' || $data->jam == 00) {
                    return $data->menit . ' menit';
                } elseif ($data->menit == '00' || $data->menit == 00) {
                    return $data->jam . ' jam';
                } else {
                    return $data->jam . ' jam ' . $data->menit . ' menit';
                }
            })
            ->addColumn('action', function($data) {
                $btnEdit = '<button type="button" class="btn btn-warning btn-edit" title="Edit" onclick="editPaket(\'' . $data->pk_id . '\', \'' . $data->pk_nama . '\', \'' . $data->pk_time . '\')">Edit</button>';
                $btnHapus = '<button type="button" class="btn btn-danger btn-hapus" title="Hapus" onclick="hapusPaket(\'' . $data->pk_id . '\')">Hapus</button>';

                return '<div class="btn-group btn-group-sm">' . $btnEdit . $btnHapus . '</div>';
            })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function store(Request $request)
    {
    	DB::beginTransaction();
    	try {
    		$pk_id = MPaket::max('pk_id') + 1;

    		MPaket::insert([
    			'pk_id'		=> $pk_id,
    			'pk_nama'	=> $request->paket,
    			'pk_time'	=> $request->waktu
    		]);

    		DB::commit();  
    		return response()->json([
    			'status' => 'berhasil'
    		]);
    	
    	} catch (\Exception $e) {
    		DB::rollback();
    		return response()->json([
    			'status' => 'gagal',
    			'message' => $e->getMessage()
    		]);
    	}
    }

    public function update(Request $request)
    {
    	DB::beginTransaction();
    	try {
    		MPaket::where('pk_id', $request->id_paket)
    		->update([
    			'pk_nama'	=> $request->paket,
    			'pk_time'	=> $request->waktu
    		]);

    		DB::commit();  
    		return response()->json([
    			'status' => 'berhasil'
    		]);
    	
    	} catch (\Exception $e) {
    		DB::rollback();
    		return response()->json([
    			'status' => 'gagal',
    			'message' => $e->getMessage()
    		]);
    	}
    }

    public function delete(Request $request)
    {
    	DB::beginTransaction();
    	try {
    		MPaket::where('pk_id', $request->pk_id)->delete();
    		
    		DB::commit();  
    		return response()->json([
    			'status' => 'berhasil'
    		]);
    	
    	} catch (\Exception $e) {
    		DB::rollback();
    		return response()->json([
    			'status' => 'gagal',
    			'message' => $e->getMessage()
    		]);
    	}
    }

	public function apiGetData()
    {
        DB::beginTransaction();
    	try {
    		$data = MPaket::all();

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
