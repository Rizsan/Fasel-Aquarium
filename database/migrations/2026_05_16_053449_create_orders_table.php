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
        Schema::create('orders', function (Blueprint $table) {

            $table->id();

            // User pemilik order
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            // Nomor order unik
            $table->string('order_number')->unique();

            // Total pembayaran
            $table->decimal('total_price', 12, 2);

            /*
            |--------------------------------------------------------------------------
            | STATUS ORDER
            |--------------------------------------------------------------------------
            | pending     = belum bayar
            | paid        = pembayaran berhasil
            | processing  = sedang diproses
            | shipped     = dikirim
            | completed   = selesai
            | cancelled   = dibatalkan / expired
            */
            $table->enum('status', [
                'pending',
                'paid',
                'processing',
                'shipped',
                'completed',
                'cancelled'
            ])->default('pending');

            /*
            |--------------------------------------------------------------------------
            | MIDTRANS
            |--------------------------------------------------------------------------
            */

            // Snap token
            $table->text('snap_token')->nullable();

            // contoh:
            // bank_transfer, gopay, qris, dll
            $table->string('payment_type')->nullable();

            // pending / settlement / expire / cancel
            $table->string('transaction_status')->nullable();

            // ID transaksi dari midtrans
            $table->string('transaction_id')->nullable();

            // Waktu pembayaran sukses
            $table->timestamp('paid_at')->nullable();

            // Optional catatan
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};