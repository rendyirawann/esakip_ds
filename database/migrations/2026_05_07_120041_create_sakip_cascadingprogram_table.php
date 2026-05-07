<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sakip_cascadingprogram', function (Blueprint $table) {
            $table->bigIncrements('refcascadingprogram_id');
            $table->bigInteger('refsasaran_id')->nullable();
            $table->bigInteger('refskpd_id')->nullable();
            $table->bigInteger('reftujuan_id')->nullable();
            $table->bigInteger('refmisi_id')->nullable();
            $table->bigInteger('refsasaranrenstra_id')->nullable();
            $table->bigInteger('refindikatorsasaranrenstra_id')->nullable();
            $table->bigInteger('refbidang_id')->nullable();
            $table->bigInteger('refprogram_id')->nullable();
            $table->text('uraian_sasaranprogram');
            $table->text('uraian_indikatorprogram');
            $table->bigInteger('refperiode_id')->nullable();
            $table->text('program_target');
            $table->text('program_satuan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sakip_cascadingprogram');
    }
};