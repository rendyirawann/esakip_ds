<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sakip_pegawaibappeda', function (Blueprint $table) {
            $table->bigIncrements('refpegawai_id');
            $table->integer('statusAparatur')->nullable();
            $table->text('nama_pegawai')->nullable();
            $table->text('nip')->nullable();
            $table->bigInteger('refeselon_id')->nullable();
            $table->bigInteger('reftitle_id')->nullable();
            $table->bigInteger('refbidangbappeda_id')->nullable();
            $table->text('no_hp')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sakip_pegawaibappeda');
    }
};