<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->uuid('id_barang')->primary();
            $table->uuid('id_user_penjual');
            $table->string('nama_barang');
            $table->text('deskripsi_barang')->nullable();
            $table->string('jenis_barang');
            $table->decimal('harga', 10, 2);
            $table->integer('stok');
            $table->string('foto_barang', 255)->nullable();

            $table->integer('kalori_kkal')->nullable();
            $table->decimal('protein_g', 5, 2)->nullable();
            $table->decimal('lemak_g', 5, 2)->nullable();
            $table->decimal('karbohidrat_g', 5, 2)->nullable();
            $table->decimal('serat_g', 5, 2)->nullable();
            $table->decimal('gula_g', 5, 2)->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_user_penjual')->references('id_user')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
