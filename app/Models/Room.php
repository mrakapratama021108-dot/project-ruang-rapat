<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['nama_ruangan', 'kapasitas', 'fasilitas'];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}