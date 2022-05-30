<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DHasilLatihan extends Model
{
    use HasFactory;

    protected $table = "d_hasil_latihan";

    public function getPaket()
    {
        return $this->belongsTo('App\Models\MPaket', 'id_paket', 'pk_id');
    }
}
