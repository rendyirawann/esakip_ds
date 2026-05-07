<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sakip_sasaranrenstra_p', function (Blueprint $table) {
            $table->bigIncrements('refsasaranrenstra_p_id');
            $table->bigInteger('refsasaranrenstra_id')->nullable();
            $table->text('uraian_sasaranrenstra_p');
            $table->bigInteger('refskpd_id')->nullable();
            $table->bigInteger('refsasaran_p_id')->nullable();
            $table->bigInteger('refvisi_p_id')->nullable();
            $table->bigInteger('refmisi_p_id')->nullable();
            $table->bigInteger('reftujuan_p_id')->nullable();
            $table->bigInteger('refperiode_id')->nullable();
            $table->bigInteger('reftujuanrenstra_p_id')->nullable();
            $table->text('sasaranrenstra_p_isaktif')->nullable();
            $table->text('alasan_sasaranrenstra_p')->nullable();
            $table->text('formulasi_sasaranrenstra_p')->nullable();
            $table->text('kriteria_sasaranrenstra_p')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sakip_sasaranrenstra_p');
    }
};