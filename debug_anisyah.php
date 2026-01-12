<?php
use App\Models\User;
use App\Models\Siswa;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$name = 'ANISYAH';
$users = User::where('name', 'LIKE', "%$name%")->get();

echo "Found " . $users->count() . " Users:\n";

foreach ($users as $user) {
    echo "User: {$user->name} (ID: {$user->id})\n";
    echo "Role: {$user->role}\n";
    echo "Email: {$user->email}\n";

    $siswa = $user->siswa;
    if ($siswa) {
        echo "Linked Siswa: {$siswa->nama} (ID: {$siswa->id}, NIS: {$siswa->nis})\n";
    } else {
        echo "Linked Siswa: NULL\n";
    }
    echo "--------------------\n";
}
