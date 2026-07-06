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
        Schema::table('custom_orders', function (Blueprint $table) {
            $table->string('admin_mockup_file')->nullable()->after('design_file');
            $table->text('revision_notes')->nullable()->after('notes');
            $table->integer('revision_count')->default(0)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('custom_orders', function (Blueprint $table) {
            $table->dropColumn(['admin_mockup_file', 'revision_notes', 'revision_count']);
        });
    }
};
