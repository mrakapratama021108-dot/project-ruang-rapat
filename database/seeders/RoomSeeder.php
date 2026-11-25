<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            [
                'nama_ruangan' => 'Ruang Rapat Sidang Sehat',
                'kapasitas' => 30,
                'fasilitas' => 'Smart TV, AC, Microphone, Wi-Fi',
            ],
            [
                'nama_ruangan' => 'Ruang Rapat Sidang Cerdas',
                'kapasitas' => 15,
                'fasilitas' => 'Smart TV, Kipas, Whiteboard, Speaker, Wi-Fi',
            ],
            [
                'nama_ruangan' => 'Ruang Rapat Sidang Ceria',
                'kapasitas' => 15,
                'fasilitas' => 'Smart TV, Kipas, Speaker, Wi-Fi',
            ],
        ];

        foreach ($rooms as$room) {
            Room::create($room);
        }
    }
}