<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'room_id', 'judul_rapat', 'penanggung_jawab',
        'jumlah_peserta', 'fasilitas_tambahan',
        'tanggal_rapat', 'jam_mulai', 'jam_selesai', 'status'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}