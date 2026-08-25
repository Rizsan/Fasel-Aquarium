<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mortality_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnDelete();
            $table->date('date');
            $table->unsignedInteger('quantity');
            $table->string('cause')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamps();

            $table->index(['date', 'product_id']);
            $table->index('cause');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mortality_records');
    }
};
