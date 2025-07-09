<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueConstraintToTagihanBulanan extends Migration
{
    public function up()
    {
        Schema::table('tagihan_bulanan', function (Blueprint $table) {
            // Tambahkan unique constraint
            $table->unique(['register_id', 'bulan'], 'tagihan_register_bulan_unique');
        });
    }

    public function down()
    {
        Schema::table('tagihan_bulanan', function (Blueprint $table) {
            // Hapus constraint jika di-rollback
            $table->dropUnique('tagihan_register_bulan_unique');
        });
    }
}

