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
        Schema::create('tagihan_bulanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('register_id')->constrained('registers')->onDelete('cascade');
            $table->string('bulan');
            $table->date('jatuh_tempo');
            $table->enum('status', ['belum_lunas', 'tertunda', 'lunas'])->default('belum_lunas');
            $table->string('bukti_transfer')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihan_bulanan');
    }
};
