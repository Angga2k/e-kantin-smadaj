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
        // 1. Update tabel history_penarikan (Untuk Disbursement/Penarikan)
        Schema::table('history_penarikan', function (Blueprint $table) {
            // Kolom ID unik untuk Xendit
            $table->string('external_id')->nullable()->after('id_penarikan')->index();
            // Kolom untuk mencatat alasan jika transfer gagal
            $table->string('failure_code')->nullable()->after('status');
        });

        // 2. Update tabel transaksi (Untuk Invoice/Pembelian Siswa)
        Schema::table('transaksi', function (Blueprint $table) {
            // Kita tambahkan external_id agar konsisten dengan controller nanti
            // (Meskipun Anda punya id_order_gateway, istilah external_id lebih standar di Xendit)
            $table->string('external_id')->nullable()->after('id_transaksi')->index();

            // Tambah URL pembayaran Xendit (opsional, buat redirect ulang jika user close tab)
            $table->string('payment_link')->nullable()->after('total_harga');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('history_penarikan', function (Blueprint $table) {
            $table->dropColumn(['external_id', 'failure_code']);
        });

        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn(['external_id', 'payment_link']);
        });
    }
};
