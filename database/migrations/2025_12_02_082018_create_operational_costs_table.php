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
        Schema::create('operational_costs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cost_account_id')->constrained('cost_accounts')->onDelete('cascade');
            $table->decimal('nominal', 15, 2);
            $table->date('tanggal');
            $table->text('deskripsi')->nullable();
            $table->timestamps();

            $table->index(['cost_account_id', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operational_costs');
    }
};
