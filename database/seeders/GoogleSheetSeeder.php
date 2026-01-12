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
        // URL for Student Data (GID=0)
        $url = 'https://docs.google.com/spreadsheets/d/e/2PACX-1vQE3K6fsKmQLDCuYJajLi1P0NGJgOlIjCG20M5HbmpF_HNYcdMxIzMV6WSOHT4pncvpg2DXoJL8lcM4/pub?gid=0&single=true&output=csv';

        $this->command->info("Downloading Student data from Google Sheet...");
        $csvData = file_get_contents($url);

        if ($csvData === false) {
            $this->command->error("Failed to download CSV.");
            return;
        }

        $rows = array_map('str_getcsv', explode("\n", $csvData));
        $header = array_shift($rows); // Header: NIS, Nama Lengkap, JK, Kelas, Wali Kelas, Kontak Orang Tua

        $this->command->info("Processing " . count($rows) . " rows...");

        foreach ($rows as $row) {
            if (count($row) < 4)
                continue;

            // Map columns based on new structure
            $nis = trim($row[0]);
            $nama = trim($row[1]);
            $jk = trim($row[2]);
            $kelas = trim($row[3]);
            $waliKelas = isset($row[4]) ? trim($row[4]) : null;
            $kontakOrtu = isset($row[5]) ? trim($row[5]) : null;

            if (empty($nis))
                continue;

            // Normalize Role
            $role = 'siswa';

            // Generate Email
            $email = $nis . '@yamis.com';

            // Create/Update User
            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $nama,
                    'password' => Hash::make($nis), // Password is NIS
                    'role' => $role
                ]
            );

            // Create/Update Siswa Record
            Siswa::updateOrCreate(
                ['nis' => $nis],
                [
                    'user_id' => $user->id,
                    'nama' => $nama,
                    'kelas' => $kelas, // Now mapped correctly
                    'jenis_kelamin' => $jk,
                    'wali_kelas' => $waliKelas,
                    'kontak_orang_tua' => $kontakOrtu,
                ]
            );
        }

        $this->command->info("Seeding completed successfully!");
    }
}
