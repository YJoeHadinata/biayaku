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
        Schema::create('production_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->decimal('jumlah_output', 10, 4); // berapa unit output
            $table->date('tanggal_produksi');
            $table->decimal('hpp_total', 15, 2)->nullable(); // otomatis: sum(material takaran × harga)
            $table->decimal('hpp_per_unit', 15, 2)->nullable(); // otomatis: hpp_total / jumlah_output
            $table->decimal('biaya_tambahan', 15, 2)->default(0); // upah, packaging, overhead
            $table->decimal('total_hpp_dengan_tambahan', 15, 2)->nullable(); // hpp_total + biaya_tambahan
            $table->decimal('total_hpp_per_unit_final', 15, 2)->nullable(); // total dengan tambahan / jumlah_output
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index(['product_id', 'tanggal_produksi']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_batches');
    }
};
