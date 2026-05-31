<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            // Tambah kolom denda (Rp/hari) dan status bayar
            $table->integer('denda')->default(0)->after('keterangan');
            $table->boolean('denda_dibayar')->default(false)->after('denda');
            // Tambah foreign key ke tabel buku dan users
            $table->unsignedBigInteger('buku_id')->nullable()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn(['denda', 'denda_dibayar', 'buku_id', 'user_id']);
        });
    }
};
