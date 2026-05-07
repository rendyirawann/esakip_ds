<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sakip_indikatorcascadingkegiatan_triwulan', function (Blueprint $table) {
            $table->bigIncrements('refindikatorkegiatantriwulan_id');
            $table->bigInteger('refindikatorkegiatan_id')->nullable();
            $table->bigInteger('refcascadingprogram_id')->nullable();
            $table->bigInteger('refcascadingkegiatan_id')->nullable();
            $table->bigInteger('refsasaranrenstra_id')->nullable();
            $table->bigInteger('refindikatorsasaranrenstra_id')->nullable();
            $table->bigInteger('refskpd_id')->nullable();
            $table->bigInteger('refperiode_id')->nullable();
            $table->bigInteger('reftriwulan_id')->nullable();
            $table->bigInteger('refprogram_id')->nullable();
            $table->bigInteger('refkegiatan_id')->nullable();
            $table->text('triwulan_target_rkt')->nullable();
            $table->text('triwulan_target_rkt_p')->nullable();
            $table->text('triwulan_target_pk')->nullable();
            $table->text('triwulan_target_pk_p')->nullable();
            $table->text('triwulan_realisasi')->nullable();
            $table->text('triwulan_capaian')->nullable();
            $table->text('triwulan_keterangan')->nullable();
            $table->text('triwulan_analisis')->nullable();
            $table->text('triwulan_keterangan_pk_p')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sakip_indikatorcascadingkegiatan_triwulan');
    }
};