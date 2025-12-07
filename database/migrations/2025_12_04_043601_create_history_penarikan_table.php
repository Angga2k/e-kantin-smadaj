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
        Schema::create('history_penarikan', function (Blueprint $table) {
            $table->uuid('id_penarikan')->primary();

            // Relasi ke tabel dompet (menggunakan id_dompet sebagai foreign key)
            $table->foreignUuid('id_dompet')->constrained('dompet', 'id_dompet')->onDelete('cascade');

            // Kolom jumlah tipe TEXT karena akan dienkripsi seperti saldo dompet
            $table->text('jumlah');

            // Informasi tujuan penarikan
            $table->string('bank_tujuan');
            $table->string('no_rekening');

            // Status penarikan: default 'pending' (menunggu persetujuan/proses)
            $table->enum('status', ['pending', 'berhasil', 'gagal'])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_penarikan');
    }
};
