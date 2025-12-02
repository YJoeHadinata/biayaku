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
        Schema::table('materials', function (Blueprint $table) {
            $table->string('base_unit')->nullable()->after('unit');
            $table->decimal('qty_per_unit', 10, 2)->nullable()->after('base_unit');
            $table->text('deskripsi')->nullable()->after('qty_per_unit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            $table->dropColumn(['base_unit', 'qty_per_unit', 'deskripsi']);
        });
    }
};
