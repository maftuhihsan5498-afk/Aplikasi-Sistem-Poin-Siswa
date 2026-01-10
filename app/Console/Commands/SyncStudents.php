<?php

namespace App\Console\Commands;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class SyncStudents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-students';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync student data from Google Sheet CSV';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $url = 'https://docs.google.com/spreadsheets/d/e/2PACX-1vQE3K6fsKmQLDCuYJajLi1P0NGJgOlIjCG20M5HbmpF_HNYcdMxIzMV6WSOHT4pncvpg2DXoJL8lcM4/pub?gid=0&single=true&output=csv';

        $this->info('Fetching data from Google Sheet...');

        try {
            $csvData = file_get_contents($url);
            if ($csvData === false) {
                $this->error('Failed to fetch data from URL.');
                return;
            }

            $lines = explode("\n", $csvData);
            $header = str_getcsv(array_shift($lines));

            // Expected header: NIS,Nama Lengkap,JK,Kelas,Wali Kelas,Kontak Orang Tua
            // We map indices: 0:NIS, 1:Nama, 2:JK, 3:Kelas, 4:Wali, 5:Kontak

            $this->info('Processing ' . count($lines) . ' students...');
            $bar = $this->output->createProgressBar(count($lines));

            DB::beginTransaction();

            foreach ($lines as $line) {
                if (empty(trim($line)))
                    continue;

                $row = str_getcsv($line);

                // Ensure row has enough columns
                if (count($row) < 6)
                    continue;

                $nis = trim($row[0]);
                $nama = trim($row[1]);
                $jk = trim($row[2]);
                $kelas = trim($row[3]);
                $wali = trim($row[4]);
                $kontak = trim($row[5]);

                // Create or Update User
                // Email pattern: NIS@student.school.id (Change domain as needed)
                $email = $nis . '@student.sch.id';

                $user = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name' => $nama,
                        'password' => Hash::make($nis), // Default password is NIS
                        'role' => 'siswa',
                    ]
                );

                // Update info if user exists but name changed? 
                // Currently strictly keeping email unique. 
                // If user exists, we might want to ensure role is siswa?
                if ($user->role !== 'siswa') {
                    // Log warning? Or skip?
                    // Assuming only students have this email pattern.
                }

                // Create or Update Siswa
                Siswa::updateOrCreate(
                    ['nis' => $nis],
                    [
                        'user_id' => $user->id,
                        'nama' => $nama,
                        'kelas' => $kelas,
                        'jenis_kelamin' => $jk,
                        'wali_kelas' => $wali,
                        'kontak_orang_tua' => $kontak,
                    ]
                );

                $bar->advance();
            }

            DB::commit();
            $bar->finish();
            $this->newLine();
            $this->info('Student data synced successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('An error occurred: ' . $e->getMessage());
            Log::error('SyncStudents Limit: ' . $e->getMessage());
        }
    }
}
