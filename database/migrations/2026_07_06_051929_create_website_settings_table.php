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
        Schema::create('website_settings', function (Blueprint $table) {
            $table->id();
            
            // Identitas Website
            $table->string('app_name')->default('Fasel Aquarium');
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->text('slogan')->nullable();
            
            // Informasi Kontak
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->text('address')->nullable();
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();
            
            // Lokasi
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // Pengaturan Umum
            $table->string('timezone')->default('Asia/Jakarta');
            $table->string('date_format')->default('d/m/Y');
            $table->integer('products_per_page')->default(12);
            $table->boolean('maintenance_mode')->default(false);
            
            // Copyright
            $table->string('copyright_text')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_settings');
    }
};
