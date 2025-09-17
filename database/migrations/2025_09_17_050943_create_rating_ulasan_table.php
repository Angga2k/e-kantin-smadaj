<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rating_ulasan', function (Blueprint $table) {
            $table->uuid('id_rating')->primary();
            $table->uuid('id_barang');
            $table->uuid('id_user_siswa');
            $table->unsignedBigInteger('id_detail_transaksi')->unique();
            $table->integer('rating');
            $table->text('ulasan')->nullable();
            $table->timestamps();

            $table->foreign('id_barang')->references('id_barang')->on('barang')->onDelete('cascade');
            $table->foreign('id_user_siswa')->references('id_user')->on('users')->onDelete('cascade');
            $table->foreign('id_detail_transaksi')->references('id_detail')->on('detail_transaksi')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rating_ulasan');
    }
};