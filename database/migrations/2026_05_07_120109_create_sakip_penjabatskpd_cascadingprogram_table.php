<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sakip_penjabatskpd_cascadingprogram', function (Blueprint $table) {
            $table->bigIncrements('refpenjabatcascadingprogram_id');
            $table->bigInteger('refpenjabatskpd_id')->nullable();
            $table->bigInteger('refeselon_id')->nullable();
            $table->bigInteger('refcascadingprogram_id')->nullable();
            $table->bigInteger('refindikatorprogram_id')->nullable();
            $table->bigInteger('refskpd_id')->nullable();
            $table->bigInteger('refperiode_id')->nullable();
            $table->bigInteger('refsasaranrenstra_id')->nullable();
            $table->bigInteger('refindikatorsasaranrenstra_id')->nullable();
            $table->bigInteger('refbidang_id')->nullable();
            $table->bigInteger('refprogram_id')->nullable();
            $table->text('uraian_sasaranprogram')->nullable();
            $table->text('uraian_indikatorprogram')->nullable();
            $table->text('program_target')->nullable();
            $table->text('program_satuan')->nullable();
            $table->text('target_rkt')->nullable();
            $table->text('target_rkt_p')->nullable();
            $table->text('target_pk')->nullable();
            $table->text('target_pk_p')->nullable();
            $table->text('realisasi')->nullable();
            $table->text('capaian')->nullable();
            $table->text('keterangan')->nullable();
            $table->text('analisis')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sakip_penjabatskpd_cascadingprogram');
    }
};