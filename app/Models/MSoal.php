<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MSoal extends Model
{
    use HasFactory;

    protected $table = 'm_soal';

    public function getPaket()
    {
        return $this->belongsTo('App\Models\MPaket', 's_id_paket', 'pk_id');
    }

    public function getKategori()
    {
		return $this->belongsTo('App\Models\MKategori', 's_id_kategori', 'kt_id');
    }

    public function getPilihanGanda()
    {
		return $this->hasMany('App\Models\MSoalJawaban', 'sj_id_soal', 's_id');
    }

    public function getKunci()
    {
		return $this->belongsTo('App\Models\MSoalKunciJawaban', 's_id', 'skj_id_soal');
    }
}
