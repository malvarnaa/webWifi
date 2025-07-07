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
        Schema::create('promo', function (Blueprint $table) {
            $table->id();
            $table->string('nama_promo');
            $table->string('kode_promo')->unique();
            $table->text('deskripsi');
            $table->enum('jenis_promo', ['diskon', 'gratis_bulan', 'cashback'])->default('diskon');
            $table->decimal('diskon', 8, 2)->nullable();
            $table->enum('tipe_diskon', ['persen', 'nominal'])->default('persen');
            $table->decimal('minimal_pembelian', 10, 2)->default(0);
            $table->dateTime('waktu_mulai')->nullable();
            $table->dateTime('waktu_berakhir')->nullable();            
            $table->integer('batas_penggunaan')->nullable(); 
            $table->integer('jumlah_digunakan')->default(0);
            $table->string('gambar')->nullable(); // banner promo
            $table->integer('limit_per_user')->nullable(); // berapa kali user boleh pakai
            $table->foreignId('paket_id')->nullable()->constrained('pakets')->nullOnDelete(); 
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promo');
    }
};
