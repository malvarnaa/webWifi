<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('registers', function (Blueprint $table) {
            $table->string('selfie_ktp')->nullable()->after('foto_ktp');
            $table->string('foto_rumah')->nullable()->after('selfie_ktp');
        });
    }

    /**
     * Reverse the migrations.
     */
     public function down()
    {
        Schema::table('registers', function (Blueprint $table) {
            $table->dropColumn(['selfie_ktp', 'foto_rumah']);
        });
    }
};
