<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->string('foto_profile')->nullable()->after('jenis_kelamin');
        });

        Schema::table('civitas_akademik', function (Blueprint $table) {
            $table->string('foto_profile')->nullable()->after('jenis_kelamin');
        });

        Schema::table('penjual', function (Blueprint $table) {
            $table->string('foto_profile')->nullable()->after('nama_bank');
        });
    }

    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropColumn('foto_profile');
        });

        Schema::table('civitas_akademik', function (Blueprint $table) {
            $table->dropColumn('foto_profile');
        });

        Schema::table('penjual', function (Blueprint $table) {
            $table->dropColumn('foto_profile');
        });
    }
};
