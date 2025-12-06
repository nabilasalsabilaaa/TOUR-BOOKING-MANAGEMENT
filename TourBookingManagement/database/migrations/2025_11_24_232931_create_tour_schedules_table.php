<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Membuat tabel `tour_schedules` yang menyimpan jadwal keberangkatan
     * untuk masing-masing paket tour.
     */
    public function up(): void
    {
        Schema::create('tour_schedules', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel tours (1 tour memiliki banyak jadwal)
            // cascadeOnDelete → jika tour dihapus, semua jadwal ikut terhapus
            $table->foreignId('tour_id')
                ->constrained('tours')
                ->cascadeOnDelete();

            // Tanggal keberangkatan tour
            $table->date('date');

            // Sisa slot/peserta yang dapat melakukan booking
            $table->integer('available_slots');

            // Status jadwal: aktif (bisa dibooking) / tidak aktif
            $table->boolean('is_active')->default(true);

            // created_at & updated_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * Menghapus tabel ketika rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_schedules');
    }
};
