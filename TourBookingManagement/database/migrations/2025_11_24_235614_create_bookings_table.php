<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Membuat tabel `bookings` untuk menyimpan transaksi pemesanan tour
     * oleh customer. Mencatat jadwal tour, jumlah peserta, harga total,
     * status pemesanan, dan identitas customer.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel users (customer yang melakukan booking)
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Relasi ke jadwal tour (tour_schedules)
            $table->foreignId('tour_schedule_id')
                ->constrained('tour_schedules')
                ->cascadeOnDelete();

            // Jumlah peserta dalam booking
            $table->unsignedInteger('guests');

            // Total harga booking (guests * harga per orang)
            $table->decimal('total_price', 12, 2);

            // Status booking
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])
                ->default('pending');

            // Informasi customer (diambil dari profil saat booking)
            $table->string('customer_name');
            $table->string('customer_phone');

            // Catatan tambahan dari customer
            $table->text('notes')->nullable();

            // created_at & updated_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * Menghapus tabel bookings jika rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
