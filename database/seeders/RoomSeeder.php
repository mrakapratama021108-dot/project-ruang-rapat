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
                'nama_ruangan' => 'Ruang Sidang Sehat',
                'kapasitas' => 30,
                'fasilitas' => 'Smart TV, AC, Wi-Fi, Sound System, Microphone, Mic Wireless',
            ],
            [
                'nama_ruangan' => 'Ruang Sidang Cerdas',
                'kapasitas' => 15,
                'fasilitas' => 'Smart TV, AC, Wi-Fi, Sound System, Microphone, bangku tanpa meja 6',
            ],
            [
                'nama_ruangan' => 'Ruang Sidang Ceria',
                'kapasitas' => 15,
                'fasilitas' => 'Smart TV, AC, Wi-Fi, Sound System, Microphone, bangku tanpa meja 11',
            ],
            [
                'nama_ruangan' => 'Ruang Sidang Terpadu Sehat – Cerdas',
                'kapasitas' => 45,
                'fasilitas' => 'Partisi Terbuka (2 Ruangan), Smart TV 2, AC, Wi-Fi, Sound system, Microphone, Mic Wireless, bangku tanpa meja 6',
            ], // <-- Bagian ini sebelumnya kurang ],
            [
                'nama_ruangan' => 'Ruang Sidang Terpadu Cerdas – Ceria',
                'kapasitas' => 30,
                'fasilitas' => 'Partisi Terbuka (2 Ruangan), Smart TV 2, AC, Wi-Fi, Microphone, Bangku tanpa Meja 17',
            ],
            [
                'nama_ruangan' => 'Ruang Sidang Terpadu Sehat – Cerdas – Ceria',
                'kapasitas' => 100,
                'fasilitas' => 'Partisi Terbuka Penuh (3 Ruangan), Smart TV 3, AC, Wi-Fi, Microphone, Mic Wireless, Bangku tanpa Meja 17',
            ],
        ];

        foreach ($rooms as $room) {
            Room::updateOrCreate(
                ['nama_ruangan' => $room['nama_ruangan']],
                [
                    'kapasitas' => $room['kapasitas'],
                    'fasilitas' => $room['fasilitas'],
                ]
            );
        }
    }
}