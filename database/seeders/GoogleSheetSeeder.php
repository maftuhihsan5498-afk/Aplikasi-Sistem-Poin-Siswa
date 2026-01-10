<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Siswa;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class GoogleSheetSeeder extends Seeder
{
    public function run()
    {
        $url = 'https://docs.google.com/spreadsheets/d/e/2PACX-1vQE3K6fsKmQLDCuYJajLi1P0NGJgOlIjCG20M5HbmpF_HNYcdMxIzMV6WSOHT4pncvpg2DXoJL8lcM4/pub?gid=1788076590&single=true&output=csv';

        $this->command->info("Downloading data from Google Sheet...");
        $csvData = file_get_contents($url);

        if ($csvData === false) {
            $this->command->error("Failed to download CSV.");
            return;
        }

        $rows = array_map('str_getcsv', explode("\n", $csvData));
        $header = array_shift($rows); // Header: Username, Password, Role, Nama

        $this->command->info("Processing " . count($rows) . " rows...");

        foreach ($rows as $row) {
            if (count($row) < 4)
                continue;

            $username = trim($row[0]);
            $password = trim($row[1]);
            $roleRaw = trim($row[2]); // admin, guru, siswa
            $nama = trim($row[3]);
            $waliKelas = isset($row[4]) ? trim($row[4]) : null;
            $kontakOrtu = isset($row[5]) ? trim($row[5]) : null;

            if (empty($username))
                continue;

            // Normalize Role
            $role = strtolower($roleRaw);

            // Generate Email
            // Using a shorter domain as requested
            $email = $username . '@yamis.com';

            // Create/Update User
            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $nama,
                    'password' => Hash::make($password),
                    'role' => $role
                ]
            );

            // If Siswa, create/update Siswa Record
            if ($role === 'siswa') {
                Siswa::updateOrCreate(
                    ['nis' => $username],
                    [
                        'user_id' => $user->id,
                        'nama' => $nama,
                        'kelas' => 'Daftar Baru', // Default class
                        'wali_kelas' => $waliKelas,
                        'kontak_orang_tua' => $kontakOrtu,
                    ]
                );
            }
        }

        $this->command->info("Seeding completed successfully!");
    }
}
