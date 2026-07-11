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
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('cascade')->after('user_id'); // Basis produk (kosongan)
            $table->string('material')->nullable()->after('category');
            $table->string('user_design_path')->nullable()->after('design_file');
            $table->string('admin_design_path')->nullable()->after('admin_mockup_file');
            $table->decimal('price_per_item', 10, 2)->nullable()->after('quantity');
            $table->foreignId('transaction_id')->nullable()->constrained()->onDelete('set null')->after('status'); // Diisi saat checkout
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('custom_orders', function (Blueprint $table) {
            $table->dropForeign(['custom_orders_product_id_foreign']);
            $table->dropForeign(['custom_orders_transaction_id_foreign']);
            $table->dropColumn(['product_id', 'material', 'user_design_path', 'admin_design_path', 'price_per_item', 'transaction_id']);
        });
    }
};
