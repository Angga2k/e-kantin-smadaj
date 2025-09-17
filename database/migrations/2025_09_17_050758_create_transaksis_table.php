<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->uuid('id_transaksi')->primary();
            $table->string('kode_transaksi', 20)->unique();
            $table->uuid('id_user_pembeli');
            $table->decimal('total_harga', 10, 2);
            $table->string('id_order_gateway', 100)->unique();
            $table->string('metode_pembayaran', 50)->nullable();
            $table->enum('status_pembayaran', ['pending', 'success', 'failed', 'expired'])->default('pending');
            $table->datetime('waktu_transaksi')->useCurrent();
            $table->datetime('waktu_pengambilan')->useCurrent();
            $table->string('detail_pengambilan', 20);
            $table->timestamps();

            $table->foreign('id_user_pembeli')->references('id_user')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};