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
        Schema::table('orders', function (Blueprint $table) {

            // Tambahkan hanya jika belum ada
            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->enum('payment_method', ['cash', 'transfer'])
                    ->default('cash')
                    ->after('user_id');
            }

            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->enum('payment_status', ['unpaid', 'paid'])
                    ->default('unpaid')
                    ->after('payment_method');
            }

            if (!Schema::hasColumn('orders', 'status')) {
                $table->enum('status', [
                    'waiting_payment',
                    'ready_for_pickup',
                    'processing',
                    'completed',
                    'cancelled',
                ])->default('waiting_payment')->after('payment_status');
            }

            if (!Schema::hasColumn('orders', 'payment_proof')) {
                $table->string('payment_proof')->nullable()->after('status');
            }

            if (!Schema::hasColumn('orders', 'payment_proof_uploaded_at')) {
                $table->timestamp('payment_proof_uploaded_at')->nullable()->after('payment_proof');
            }

            if (!Schema::hasColumn('orders', 'notes')) {
                $table->text('notes')->nullable()->after('payment_proof_uploaded_at');
            }

            if (!Schema::hasColumn('orders', 'total_amount')) {
                $table->unsignedBigInteger('total_amount')->default(0)->after('notes');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $columns = [
                'payment_method',
                'payment_status',
                'status',
                'payment_proof',
                'payment_proof_uploaded_at',
                'notes',
                'total_amount',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
