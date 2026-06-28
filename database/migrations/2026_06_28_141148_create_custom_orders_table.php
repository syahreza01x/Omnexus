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
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('customer_name');
            $table->string('whatsapp_number');
            $table->string('category'); // 'konveksi' or 'merchandise'
            $table->string('product'); // name of the product or 'lainnya'
            $table->string('other_product_name')->nullable();
            $table->integer('quantity');
            $table->string('color');
            $table->string('size')->nullable(); // nullable since it's only for konveksi
            $table->string('material_type');
            $table->string('production_technique'); // Sablon, Bordir, Printing, DTF, UV Print, Lainnya
            $table->date('deadline');
            $table->string('design_file')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('menunggu_review'); // menunggu_review, menunggu_persetujuan_customer, diproses, produksi, siap_diambil_dikirim, selesai
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
