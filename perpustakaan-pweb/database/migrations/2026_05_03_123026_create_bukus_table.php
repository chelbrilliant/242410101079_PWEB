<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buku', function (Blueprint $table) {
            $table->id();
            $table->string('kode_buku')->unique();
            $table->string('judul');
            $table->string('pengarang');
            $table->enum('kategori', ['Fiksi', 'Non-Fiksi', 'Sains', 'Teknologi', 'Sejarah', 'Agama'])->default('Non-Fiksi');
            $table->integer('tahun_terbit');
            $table->integer('stok')->default(1);
            $table->boolean('tersedia')->default(true);
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};
