<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sakip_tujuan', function (Blueprint $table) {
            $table->bigIncrements('reftujuan_id');
            $table->text('uraian_tujuan');
            $table->text('indikator_tujuan')->nullable();
            $table->bigInteger('refvisi_id')->nullable();
            $table->bigInteger('refmisi_id')->nullable();
            $table->bigInteger('refperiode_id')->nullable();
            $table->text('tujuan_isaktif')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sakip_tujuan');
    }
};