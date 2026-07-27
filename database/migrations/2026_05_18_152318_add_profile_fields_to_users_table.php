<?php
// database/migrations/xxxx_xx_xx_add_profile_fields_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->after('email');
            $table->text('address')->nullable()->after('phone');
            $table->string('profile_photo')->nullable()->after('address');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'address', 'profile_photo']);
        });
    }
};
