<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->string('jenis_kelamin')->nullable()->after('kelas');
            $table->string('wali_kelas')->nullable()->after('jenis_kelamin');
            $table->string('kontak_orang_tua')->nullable()->after('wali_kelas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropColumn(['jenis_kelamin', 'wali_kelas', 'kontak_orang_tua']);
        });
    }
};
