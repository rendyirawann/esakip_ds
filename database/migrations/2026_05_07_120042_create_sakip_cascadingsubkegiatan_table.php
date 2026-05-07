<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sakip_cascadingsubkegiatan', function (Blueprint $table) {
            $table->bigIncrements('refcascadingsubkegiatan_id');
            $table->bigInteger('refcascadingkegiatan_id')->nullable();
            $table->bigInteger('refcascadingprogram_id')->nullable();
            $table->bigInteger('refsasaranrenstra_id')->nullable();
            $table->bigInteger('refindikatorsasaranrenstra_id')->nullable();
            $table->bigInteger('refprogram_id')->nullable();
            $table->bigInteger('refkegiatan_id')->nullable();
            $table->bigInteger('refsubkegiatan_id')->nullable();
            $table->text('uraian_sasaransubkegiatan');
            $table->text('uraian_indikatorsubkegiatan');
            $table->bigInteger('refperiode_id')->nullable();
            $table->bigInteger('refskpd_id')->nullable();
            $table->text('subkegiatan_target')->nullable();
            $table->text('subkegiatan_satuan')->nullable();
            $table->text('subkegiatan_anggaran')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sakip_cascadingsubkegiatan');
    }
};