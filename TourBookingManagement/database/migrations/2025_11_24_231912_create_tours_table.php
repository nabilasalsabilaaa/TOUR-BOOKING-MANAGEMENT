<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Membuat tabel `tours` sebagai master data paket wisata.
     * Berisi informasi dasar tentang tour seperti nama, harga, durasi,
     * kapasitas, lokasi, thumbnail, dan status aktif.
     */
    public function up(): void
    {
        Schema::create('tours', function (Blueprint $table) {
            $table->id();

            // Nama paket tour
            $table->string('name');

            // Slug unik untuk URL (contoh: /tour/pulau-selayar-abc123)
            $table->string('slug')->unique();

            // Deskripsi detail tour
            $table->text('description')->nullable();

            // Lokasi atau destinasi tour
            $table->string('location')->nullable();

            // Harga paket tour
            // decimal(12,2) → bisa menampung angka besar, contoh: 999,999,999,999.99
            $table->decimal('price', 12, 2);

            // Durasi tour dalam hari
            $table->integer('duration_days')->default(1);

            // Kapasitas maksimal peserta (opsional)
            $table->integer('capacity')->nullable();

            // Path thumbnail image
            $table->string('thumbnail')->nullable();

            // Status tour: aktif / tidak
            $table->boolean('is_active')->default(true);

            // created_at & updated_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * Menghapus tabel `tours` jika rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('tours');
    }
};
