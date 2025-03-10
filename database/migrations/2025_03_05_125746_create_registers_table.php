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
        Schema::create('registers', function (Blueprint $table) {
            $table->id();
            $table->string('nama_cust');
            $table->string('nomor_hp');
            $table->string('email');
            $table->foreignId('paket_id')->constrained('pakets')->onDelete('cascade');
            $table->foreignId('prov_id')->constrained('provs')->onDelete('cascade');
            $table->foreignId('kab_id')->constrained('kabs')->onDelete('cascade');
            $table->foreignId('kec_id')->constrained('kecs')->onDelete('cascade');
            $table->string('alamat_lengkap'); 
            $table->enum('kebutuhan', ['perumahan', 'apartemen', 'bisnis']); 
            $table->integer('total_harga'); 
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registers');
    }
};
