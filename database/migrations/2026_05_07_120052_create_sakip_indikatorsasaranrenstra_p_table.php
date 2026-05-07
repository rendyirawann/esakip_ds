<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sakip_indikatorsasaranrenstra_p', function (Blueprint $table) {
            $table->bigIncrements('refindikatorsasaranrenstra_p_id');
            $table->text('uraian_indikatorsasaranrenstra_p');
            $table->bigInteger('refsasaranrenstra_p_id')->nullable();
            $table->bigInteger('refskpd_id')->nullable();
            $table->bigInteger('refperiode_id')->nullable();
            $table->text('indikatorsasaranrenstra_p_satuan')->nullable();
            $table->text('indikatorsasaranrenstra_p_target')->nullable();
            $table->text('target_rkt')->nullable();
            $table->text('target_rkt_p')->nullable();
            $table->text('target_pk')->nullable();
            $table->text('target_pk_p')->nullable();
            $table->text('realisasi')->nullable();
            $table->text('capaian')->nullable();
            $table->text('analisis')->nullable();
            $table->text('keterangan')->nullable();
            $table->text('indikatorsasaranrenstra_p_isaktif')->nullable();
            $table->text('iku_isaktif')->nullable();
            $table->text('pk_isaktif')->nullable();
            $table->text('alasan_sasaranrenstra_p')->nullable();
            $table->text('formulasi_sasaranrenstra_p')->nullable();
            $table->text('kriteria_sasaranrenstra_p')->nullable();
            $table->text('keterangan_pk')->nullable();
            $table->text('keterangan_pk_p')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sakip_indikatorsasaranrenstra_p');
    }
};