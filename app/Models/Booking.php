<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'tanggal_rapat',
        'jam_mulai',
        'jam_selesai',
        'judul_rapat',
        'penanggung_jawab',
        'jumlah_peserta',
        'fasilitas_tambahan',
        'tautan_zoom',
        'kode_zoom',
        'password_zoom',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}