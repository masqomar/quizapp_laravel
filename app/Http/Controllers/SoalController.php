<?php

namespace App\Http\Controllers;

use App\Models\DHasilLatihan;
use App\Models\MKategori;
use App\Models\MPaket;
use App\Models\MSoal;
use App\Models\MSoalJawaban;
use App\Models\MSoalKunciJawaban;
use Carbon\Carbon;
use Crypt;
use DataTables;
use DB;
use Illuminate\Http\Request;
use JWTAuth;

class SoalController extends Controller
{
    public function index()
    {
    	return view('soal.index');
    }

    public function getData(Request $request)
    {
    	$data = MSoal::with('getPaket')
    			->with('getKategori')
    			->get();

    	return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('s_id_paket', function($data) {
            	return $data->getPaket->pk_nama;
            })
            ->editColumn('s_id_kategori', function($data) {
            	return $data->getKategori->kt_nama;
            })
            ->addColumn('action', function($data) {
            	$s_id_encrypt = Crypt::encrypt($data->s_id);

				$btnJawaban = '<button type="button" class="btn btn-success btn-jawaban" title="Jawaban" onclick="jawabanSoal(\'' . $data->s_id . '\')">Kunci</button>';
                $btnEdit = '<button type="button" class="btn btn-warning btn-edit" title="Edit" onclick="editSoal(\'' . $s_id_encrypt . '\')">Edit</button>';
                $btnHapus = '<button type="button" class="btn btn-danger btn-hapus" title="Hapus" onclick="hapusSoal(\'' . $data->s_id . '\')">Hapus</button>';

                return '<div class="btn-group btn-group-sm">' . $btnJawaban . $btnEdit . $btnHapus . '</div>';
            })
        ->rawColumns(['s_pertanyaan', 'action'])
        ->make(true);
    }

    public function createSoal()
    {
    	$paket = MPaket::all();
    	$kategori = MKategori::orderBy('kt_nama', 'ASC')->get();

    	return view('soal.create', compact('paket', 'kategori'));
    }

    public function pilihKategori(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = MKategori::where('kt_id', $request->kt_id)->first();
            $data->kt_nilai_benar = json_decode($data->kt_nilai_benar);

            DB::commit();
            return response()->json([
                'status' => 'berhasil',
                'data'  => $data
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'gagal',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function storeSoal(Request $request)
    {
    	DB::beginTransaction();
    	try {
            $s_id = MSoal::max('s_id') + 1;

    		MSoal::insert([
    			's_id'			=> $s_id,
    			's_id_paket'	=> $request->soal_paket,
    			's_id_kategori'	=> $request->soal_kategori,
    			's_pertanyaan'	=> $request->soal
    		]);

    		for ($i=0; $i < count($request->soal_jawaban_abjad); $i++) { 
	    		MSoalJawaban::insert([
	    			'sj_id_soal'	=> $s_id,
	    			'sj_id'			=> $i + 1,
	    			'sj_abjad'		=> $request->soal_jawaban_abjad[$i],
                    'sj_jawaban'	=> $request->soal_jawaban[$i]
	    		]);
    		}

    		$skj_id = MSoalKunciJawaban::max('skj_id') + 1;
            $cek_tipe_soal = MKategori::where('kt_id', $request->soal_kategori)->first();

            if ($cek_tipe_soal->kt_tipe_soal == 'single_choice') {
        		MSoalKunciJawaban::insert([
        			'skj_id'			=> $skj_id,
        			'skj_id_soal'		=> $s_id,
        			'skj_id_jawaban'	=> $request->soal_kunci_single,
                    'skj_pembahasan'    => $request->soal_pembahasan
        		]);
                
            } else if ($cek_tipe_soal->kt_tipe_soal == 'multiple_choice') {
                $soal_kunci_multiple = array();
                
                for ($i=0; $i < count($request->soal_kunci_multiple) ; $i++) { 
                    $data['id_jawaban'] = $i + 1;
                    $data['nilai_jawaban'] = $request->soal_kunci_multiple[$i];

                    array_push($soal_kunci_multiple, $data);
                }

                MSoalKunciJawaban::insert([
                    'skj_id'            => $skj_id,
                    'skj_id_soal'       => $s_id,
                    'skj_id_jawaban'    => json_encode($soal_kunci_multiple),
                    'skj_pembahasan'    => $request->soal_pembahasan
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

    public function jawabanSoal(Request $request)
    {
    	DB::beginTransaction();
    	try {
            $cek = MSoal::where('s_id', $request->s_id)
                    ->with('getKategori')
                    ->first();

            if ($cek->getKategori->kt_tipe_soal == 'single_choice') {
        		$soal_jawaban = MSoalJawaban::where('sj_id_soal', $request->s_id)
        						->get();

        		$soal_kunci = MSoalKunciJawaban::where('skj_id_soal', $request->s_id)
        					->first();
                
            } else if ($cek->getKategori->kt_tipe_soal == 'multiple_choice') {
                $soal_jawaban = MSoalJawaban::where('sj_id_soal', $request->s_id)
                                ->get();

                $soal_kunci = MSoalKunciJawaban::where('skj_id_soal', $request->s_id)
                            ->first();
                $soal_kunci->skj_id_jawaban = json_decode($soal_kunci->skj_id_jawaban);
            }
            
            DB::commit();  
            return response()->json([
                'status'        => 'berhasil',
                'cek'           => $cek,
                'soal_jawaban'  => $soal_jawaban,
                'soal_kunci'    => $soal_kunci
            ]);
    	
    	} catch (\Exception $e) {
    		DB::rollback();
    		return response()->json([
    			'status' => 'gagal',
    			'message' => $e->getMessage()
    		]);
    	}
    }

    public function editSoal($s_id)
    {
    	$s_id_decrypt = Crypt::decrypt($s_id);

    	$paket = MPaket::all();
    	$kategori = MKategori::orderBy('kt_nama', 'ASC')->get();

    	$soal = MSoal::where('s_id', $s_id_decrypt)
    			->with('getPaket')
    			->with('getKategori')
    			->with('getPilihanGanda')
    			->with('getKunci')
    			->first();

        if ($soal->getKategori->kt_tipe_soal == 'multiple_choice') {
            $soal->getKategori->kt_nilai_benar = json_decode($soal->getKategori->kt_nilai_benar);
            $soal->getKunci->skj_id_jawaban = json_decode($soal->getKunci->skj_id_jawaban);
        }

        // dd($paket, $kategori, $soal);

    	return view('soal.edit', compact('paket', 'kategori', 'soal'));
    }

    public function updateSoal(Request $request)
    {
    	DB::beginTransaction();
    	try {
            // dd($request->all());
    		MSoal::where('s_id', $request->soal_id)
    		->update([
    			's_id_paket'	=> $request->soal_paket,
    			's_id_kategori'	=> $request->soal_kategori,
    			's_pertanyaan'	=> $request->soal
    		]);

    		MSoalJawaban::where('sj_id_soal', $request->soal_id)->delete();

    		for ($i=0; $i < count($request->soal_jawaban_abjad); $i++) { 
	    		MSoalJawaban::insert([
	    			'sj_id_soal'	=> $request->soal_id,
	    			'sj_id'			=> $i + 1,
	    			'sj_abjad'		=> $request->soal_jawaban_abjad[$i],
                    'sj_jawaban'	=> $request->soal_jawaban[$i]
	    		]);
    		}

            $cek_tipe_soal = MKategori::where('kt_id', $request->soal_kategori)->first();

            if ($cek_tipe_soal->kt_tipe_soal == 'single_choice') {
                MSoalKunciJawaban::where('skj_id_soal', $request->soal_id)
                ->update([
                    'skj_id_jawaban'    => $request->soal_kunci_single,
                    'skj_pembahasan'    => $request->soal_pembahasan
                ]);
                
            } else if ($cek_tipe_soal->kt_tipe_soal == 'multiple_choice') {
                $soal_kunci_multiple = array();
                
                for ($i=0; $i < count($request->soal_kunci_multiple) ; $i++) { 
                    $data['id_jawaban'] = $i + 1;
                    $data['nilai_jawaban'] = $request->soal_kunci_multiple[$i];

                    array_push($soal_kunci_multiple, $data);
                }

                MSoalKunciJawaban::where('skj_id_soal', $request->soal_id)
                ->update([
                    'skj_id_jawaban'    => json_encode($soal_kunci_multiple),
                    'skj_pembahasan'    => $request->soal_pembahasan
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

    public function deleteSoal(Request $request)
    {
    	DB::beginTransaction();
    	try {
            MSoal::where('s_id', $request->s_id)->delete();
            MSoalJawaban::where('sj_id_soal', $request->s_id)->delete();
            MSoalKunciJawaban::where('skj_id_soal', $request->s_id)->delete();

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

    public function apiGetData($idPaket)
    {
        DB::beginTransaction();
        try {
            $data = MSoal::where('s_id_paket', $idPaket)
                ->with('getPaket')
                ->with('getKategori')
                ->with('getPilihanGanda')
                ->with('getKunci')
                ->where('s_status_soal', 'A')
                ->take(5)
                ->get();

            DB::commit();
            return response()->json([
                'data'  => $data,
                'success' => true,
                'messsage' => 'berhasil mengambil data'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function apiSimpanHasilLatihan(Request $request)
    {
        DB::beginTransaction();
        try {

            $user = JWTAuth::parseToken()->authenticate();

            if($user){
                $hasilLatihan = new DHasilLatihan();
                $hasilLatihan->id = DHasilLatihan::max('id') + 1;
                $hasilLatihan->id_user = $user->id;
                $hasilLatihan->id_paket = $request->id_paket;
                $hasilLatihan->nilai_twk = $request->nilai_twk;
                $hasilLatihan->nilai_tiu = $request->nilai_tiu;
                $hasilLatihan->nilai_tkp = $request->nilai_tkp;
                $hasilLatihan->start_waktu_mengerjakan = Carbon::parse($request->start_waktu_mengerjakan);
                $hasilLatihan->end_waktu_mengerjakan = Carbon::parse($request->end_waktu_mengerjakan);
                $hasilLatihan->save();

                DB::commit();
                return response()->json([
                    'data'  => $hasilLatihan,
                    'success' => true,
                    'messsage' => 'berhasil menyimpan data'
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
