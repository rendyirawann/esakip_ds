<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sakip_sasaranrenstra', function (Blueprint $table) {
            $table->bigIncrements('refsasaranrenstra_id');
            $table->text('uraian_sasaranrenstra');
            $table->bigInteger('refskpd_id')->nullable();
            $table->bigInteger('refsasaran_id')->nullable();
            $table->bigInteger('refvisi_id')->nullable();
            $table->bigInteger('refmisi_id')->nullable();
            $table->bigInteger('reftujuan_id')->nullable();
            $table->bigInteger('refperiode_id')->nullable();
            $table->bigInteger('reftujuanrenstra_id')->nullable();
            $table->text('sasaranrenstra_isaktif')->nullable();
            $table->text('alasan_sasaranrenstra')->nullable();
            $table->text('formulasi_sasaranrenstra')->nullable();
            $table->text('kriteria_sasaranrenstra')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sakip_sasaranrenstra');
    }
};