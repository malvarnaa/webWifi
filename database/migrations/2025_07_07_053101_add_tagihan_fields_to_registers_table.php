<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTagihanFieldsToRegistersTable extends Migration
{
    public function up(): void
    {
        Schema::table('registers', function (Blueprint $table) {
            $table->date('tanggal_aktif')->nullable();
            // $table->enum('status', ['Lunas', 'Tertunda', 'Belum Lunas'])->default('Belum Lunas')->after('status_kepelangganan');
        });
    }

    public function down(): void
    {
        Schema::table('registers', function (Blueprint $table) {
            $table->dropColumn(['tanggal_aktif']);
        });
    }
}

