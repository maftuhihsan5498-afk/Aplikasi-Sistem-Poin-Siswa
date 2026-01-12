<?php
use App\Models\User;
use App\Models\Siswa;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = '2414326@yamis.com';
$nis = '2414326';

$user = User::where('email', $email)->first();
if (!$user) {
    echo "User not found.\n";
    exit;
}

echo "Processing User: {$user->name} (ID: {$user->id})\n";

// Find target Siswa
$targetSiswa = Siswa::where('nis', $nis)->first();

if ($targetSiswa) {
    echo "Found existing Siswa with NIS $nis (ID: {$targetSiswa->id})\n";

    // Check for collision
    if ($targetSiswa->user_id && $targetSiswa->user_id != $user->id) {
        echo "Warning: This Siswa is owned by another user (ID: {$targetSiswa->user_id}). Overwriting ownership.\n";
    }

    // Update ownership
    $targetSiswa->user_id = $user->id;
    $targetSiswa->save();
    echo "Linked User to Siswa ID {$targetSiswa->id}\n";

    // Clean up duplicates
    // Find any OTHER siswa records belonging to this user
    $duplicates = Siswa::where('user_id', $user->id)->where('id', '!=', $targetSiswa->id)->get();
    foreach ($duplicates as $dup) {
        echo "Deleting duplicate Siswa record (ID: {$dup->id}, NIS: {$dup->nis})\n";
        $dup->delete();
    }

} else {
    echo "Siswa with NIS $nis not found.\n";
    // Check if user has a siswa record with a WRONG NIS (start with 2024...)
    $currentSiswa = $user->siswa;
    if ($currentSiswa) {
        echo "Updating NIS of current Siswa record (ID: {$currentSiswa->id}) from {$currentSiswa->nis} to $nis\n";
        $currentSiswa->nis = $nis;
        $currentSiswa->save();
    } else {
        echo "User has no Siswa record. Nothing to do. Seeder will create it.\n";
    }
}
