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
        Schema::create('custom_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Basis produk (kosongan)
            $table->string('category')->nullable();
            $table->string('material')->nullable();
            $table->integer('quantity')->default(1);
            $table->string('user_design_path')->nullable();
            $table->text('notes')->nullable();
            $table->string('admin_design_path')->nullable();
            $table->decimal('price_per_item', 10, 2)->nullable();
            $table->enum('status', ['pending', 'designing', 'revision', 'approved', 'rejected', 'in_cart', 'completed'])->default('pending');
            $table->foreignId('transaction_id')->nullable()->constrained()->onDelete('set null'); // Diisi saat checkout
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_orders');
    }
};
