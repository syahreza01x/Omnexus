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
        Schema::table('users', function (Blueprint $table) {
            $table->string('profile_photo_path')->nullable()->after('google_id');
            $table->string('phone')->nullable()->after('profile_photo_path');
            $table->string('address_line')->nullable()->after('phone');
            $table->string('city')->nullable()->after('address_line');
            $table->string('province')->nullable()->after('city');
            $table->string('postal_code')->nullable()->after('province');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'profile_photo_path',
                'phone',
                'address_line',
                'city',
                'province',
                'postal_code',
            ]);
        });
    }
};
