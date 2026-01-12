<?php
use App\Models\User;
use App\Models\Siswa;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$name = 'ANISYAH';
$targetUser = User::find(364); // The one currently logged in (presumably)
$redundantUser = User::find(88); // The one created by seeder incorrectly/superfluously

if (!$targetUser || !$redundantUser) {
    echo "One of the users was not found. Aborting manual merge.\n";
    exit;
}

echo "Merging User ID {$redundantUser->id} into ID {$targetUser->id}...\n";

DB::transaction(function () use ($targetUser, $redundantUser) {
    // 1. Check Siswa attached to Redundant
    $siswa = $redundantUser->siswa;
    if ($siswa) {
        $siswa->user_id = $targetUser->id;
        $siswa->save();
        echo "Moved Siswa ID {$siswa->id} to User ID {$targetUser->id}.\n";
    }

    // 3. Delete Redundant (FIRST, to free up the email)
    $redundantUser->delete();
    echo "Deleted User ID {$redundantUser->id}.\n";

    // 2. Update Target User Email
    $targetUser->email = '2414326@yamis.com';
    $targetUser->save();
    echo "Updated User ID {$targetUser->id} email to 2414326@yamis.com\n";
});

echo "Merge complete.\n";
