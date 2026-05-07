<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sakip_indikatorsasaranrenstra', function (Blueprint $table) {
            $table->bigIncrements('refindikatorsasaranrenstra_id');
            $table->text('uraian_indikatorsasaranrenstra');
            $table->bigInteger('refsasaranrenstra_id')->nullable();
            $table->bigInteger('refskpd_id')->nullable();
            $table->bigInteger('refperiode_id')->nullable();
            $table->text('indikatorsasaranrenstra_satuan')->nullable();
            $table->text('indikatorsasaranrenstra_target')->nullable();
            $table->text('target_rkt')->nullable();
            $table->text('target_rkt_p')->nullable();
            $table->text('target_pk')->nullable();
            $table->text('target_pk_p')->nullable();
            $table->text('realisasi')->nullable();
            $table->text('capaian')->nullable();
            $table->text('analisis')->nullable();
            $table->text('keterangan')->nullable();
            $table->text('indikatorsasaranrenstra_isaktif')->nullable();
            $table->text('iku_isaktif')->nullable();
            $table->text('pk_isaktif')->nullable();
            $table->text('alasan_sasaranrenstra')->nullable();
            $table->text('formulasi_sasaranrenstra')->nullable();
            $table->text('kriteria_sasaranrenstra')->nullable();
            $table->text('keterangan_pk')->nullable();
            $table->text('keterangan_pk_p')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sakip_indikatorsasaranrenstra');
    }
};