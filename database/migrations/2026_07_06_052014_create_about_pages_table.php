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
        Schema::create('about_pages', function (Blueprint $table) {
            $table->id();
            
            // Konten Utama
            $table->string('title')->default('Tentang Kami');
            $table->longText('about_content')->nullable();
            $table->longText('why_choose_us')->nullable();
            $table->longText('how_to_shop')->nullable();
            $table->longText('facilities')->nullable();
            
            // Informasi Kontak
            $table->text('contact_address')->nullable();
            $table->string('contact_whatsapp')->nullable();
            $table->string('contact_instagram')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('operation_hours')->nullable();
            
            // Galeri Foto
            $table->string('gallery_1')->nullable();
            $table->string('gallery_2')->nullable();
            $table->string('gallery_3')->nullable();
            $table->string('gallery_4')->nullable();
            $table->string('gallery_5')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_pages');
    }
};
