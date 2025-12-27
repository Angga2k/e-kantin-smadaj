<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rekening_tujuan', function (Blueprint $table) {
            $table->uuid('id_rekening')->primary();

            // Relasi ke tabel users (pemilik rekening)
            $table->foreignUuid('id_user')->constrained('users', 'id_user')->onDelete('cascade');

            $table->string('nama_bank', 50);

            // Tipe TEXT agar muat menampung hasil enkripsi Laravel
            $table->text('no_rekening');

            $table->string('atas_nama', 100);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekening_tujuan');
    }
};
