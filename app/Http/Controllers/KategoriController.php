<?php

namespace App\Http\Controllers;

use App\Models\MKategori;
use DataTables;
use DB;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
    	return view('kategori.index');
    }

    public function getData(Request $request)
    {
		$data = MKategori::all();

    	return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('kt_tipe_soal', function($data) {
            	if ($data->kt_tipe_soal == 'single_choice') {
	            	return 'Single Choice';
            	} elseif ($data->kt_tipe_soal == 'multiple_choice') {
                    return 'Multiple Choice';
                }
            })
            ->addColumn('action', function($data) {
                $btnEdit = '';

                if ($data->kt_tipe_soal == 'single_choice') {
                    $btnEdit = '<button type="button" class="btn btn-warning btn-edit" title="Edit" onclick="editKategori(\'' . $data->kt_id . '\', \'' . $data->kt_nama . '\', \'' . $data->kt_tipe_soal . '\', \'' . $data->kt_nilai_benar . '\', \'' . $data->kt_nilai_salah . '\', \'' . $data->kt_nilai_kosong . '\', \'' . $data->kt_passing_grade . '\')">Edit</button>';                    
                } elseif ($data->kt_tipe_soal == 'multiple_choice') {
                    $data->kt_nilai_benar = json_decode($data->kt_nilai_benar);
                    $rentang1 = '';
                    $rentang2 = '';
                    $rentang3 = '';
                    $rentang4 = '';
                    $rentang5 = '';
                    
                    foreach ($data->kt_nilai_benar as $key => $value) {
                        if ($key == 0) {
                            $rentang1 = $value;
                        } elseif ($key == 1) {
                            $rentang2 = $value;
                        } elseif ($key == 2) {
                            $rentang3 = $value;
                        } elseif ($key == 3) {
                            $rentang4 = $value;
                        } elseif ($key == 4) {
                            $rentang5 = $value;
                        }
                    }

                    $btnEdit = '<button type="button" class="btn btn-warning btn-edit" title="Edit" onclick="editKategoriMultiple(\'' . $data->kt_id . '\', \'' . $data->kt_nama . '\', \'' . $data->kt_tipe_soal . '\', \'' . $rentang1 . '\', \'' . $rentang2 . '\', \'' . $rentang3 . '\', \'' . $rentang4 . '\', \'' . $rentang5 . '\', \'' . $data->kt_nilai_salah . '\', \'' . $data->kt_nilai_kosong . '\', \'' . $data->kt_passing_grade . '\')">Edit</button>';
                }

                $btnHapus = '<button type="button" class="btn btn-danger btn-hapus" title="Hapus" onclick="hapusKategori(\'' . $data->kt_id . '\')">Hapus</button>';

                return '<div class="btn-group btn-group-sm">' . $btnEdit . $btnHapus . '</div>';
            })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function store(Request $request)
    {
    	DB::beginTransaction();
    	try {
            // dd($request->all());
    		$kt_id = MKategori::max('kt_id') + 1;

            if ($request->tipe_soal == 'single_choice') {
                if ($request->benar == null || $request->benar == null) {
                    $request->benar = '0';
                }

        		MKategori::insert([
        			'kt_id'				=> $kt_id,
        			'kt_nama'			=> $request->kategori,
        			'kt_tipe_soal'		=> $request->tipe_soal,
        			'kt_nilai_benar'	=> $request->benar,
        			'kt_nilai_salah'	=> $request->salah,
        			'kt_nilai_kosong'	=> $request->kosong,
                    'kt_passing_grade'  => $request->passing_grade
        		]);

            } elseif ($request->tipe_soal == 'multiple_choice') {
                $benar = array();

                for ($i = 0; $i < count($request->benar_multiple); $i++) {
                    if ($request->benar_multiple[$i] == null || $request->benar_multiple[$i] == '') {
                        $value = 0;

                        array_push($benar, $value);
                    } else {
                        array_push($benar, (int)$request->benar_multiple[$i]);
                    } 
                }

                MKategori::insert([
                    'kt_id'             => $kt_id,
                    'kt_nama'           => $request->kategori,
                    'kt_tipe_soal'      => $request->tipe_soal,
                    'kt_nilai_benar'    => json_encode($benar),
                    'kt_nilai_salah'    => $request->salah,
                    'kt_nilai_kosong'   => $request->kosong,
                    'kt_passing_grade'  => $request->passing_grade
                ]);
            }
            

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
            if ($request->tipe_soal == 'single_choice') {
                if ($request->benar == null || $request->benar == null) {
                    $request->benar = '0';
                }
                
                MKategori::where('kt_id', $request->id_kategori)
                ->update([
                    'kt_nama'           => $request->kategori,
                    'kt_tipe_soal'      => $request->tipe_soal,
                    'kt_nilai_benar'    => $request->benar,
                    'kt_nilai_salah'    => $request->salah,
                    'kt_nilai_kosong'   => $request->kosong,
                    'kt_passing_grade'  => $request->passing_grade
                ]);

            } elseif ($request->tipe_soal == 'multiple_choice') {
                $benar = array();

                for ($i = 0; $i < count($request->benar_multiple); $i++) {
                    if ($request->benar_multiple[$i] == null || $request->benar_multiple[$i] == '') {
                        $value = 0;

                        array_push($benar, $value);
                    } else {
                        array_push($benar, (int)$request->benar_multiple[$i]);
                    } 
                }

                MKategori::where('kt_id', $request->id_kategori)
                ->update([
                    'kt_nama'           => $request->kategori,
                    'kt_tipe_soal'      => $request->tipe_soal,
                    'kt_nilai_benar'    => json_encode($benar),
                    'kt_nilai_salah'    => $request->salah,
                    'kt_nilai_kosong'   => $request->kosong,
                    'kt_passing_grade'  => $request->passing_grade
                ]);
            }

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
    		MKategori::where('kt_id', $request->kt_id)->delete();
    		
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
}
