<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produks';
    protected $fillable = ['nama_produk', 'harga', 
                            'ukuran', 'berat', 'nama_motif', 
                            'jenis_jahitan', 'model', 'waktu_pengerjaan', 
                            'foto', 'deskripsi'];

    public function setFilenamesAttribute($value){
        $this->attributes['foto'] = json_encode($value);
    }
}
