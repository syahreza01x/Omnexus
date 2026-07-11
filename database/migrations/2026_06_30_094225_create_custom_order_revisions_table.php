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
        Schema::create('custom_order_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('custom_order_id')->constrained()->onDelete('cascade');
            $table->enum('sender_type', ['user', 'admin']);
            $table->text('message')->nullable();
            $table->string('attachment_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_order_revisions');
    }
};
