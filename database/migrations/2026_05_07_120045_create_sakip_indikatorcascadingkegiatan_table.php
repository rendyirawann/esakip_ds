<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sakip_indikatorcascadingkegiatan', function (Blueprint $table) {
            $table->bigIncrements('refindikatorkegiatan_id');
            $table->bigInteger('refcascadingprogram_id')->nullable();
            $table->bigInteger('refcascadingkegiatan_id')->nullable();
            $table->bigInteger('refsasaranrenstra_id')->nullable();
            $table->bigInteger('refindikatorsasaranrenstra_id')->nullable();
            $table->bigInteger('refskpd_id')->nullable();
            $table->bigInteger('refperiode_id')->nullable();
            $table->bigInteger('refprogram_id')->nullable();
            $table->bigInteger('refkegiatan_id')->nullable();
            $table->text('target_rkt')->nullable();
            $table->text('target_rkt_p')->nullable();
            $table->text('target_pk')->nullable();
            $table->text('target_pk_p')->nullable();
            $table->text('realisasi')->nullable();
            $table->text('capaian')->nullable();
            $table->text('keterangan')->nullable();
            $table->text('analisis')->nullable();
            $table->text('keterangan_pk')->nullable();
            $table->text('keterangan_pk_p')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sakip_indikatorcascadingkegiatan');
    }
};