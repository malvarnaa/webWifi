<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registers', function (Blueprint $table) {
            // $table->foreignId('desa_id')->after('kec_id')->constrained('desas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('registers', function (Blueprint $table) {
            // $table->dropForeign(['desa_id']);
            // $table->dropColumn('desa_id');
        });
    }
};
