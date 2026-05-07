<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sakip_tujuan_p', function (Blueprint $table) {
            $table->bigIncrements('reftujuan_p_id');
            $table->text('uraian_tujuan_p');
            $table->text('indikator_tujuan_p')->nullable();
            $table->bigInteger('refvisi_p_id')->nullable();
            $table->bigInteger('refmisi_p_id')->nullable();
            $table->bigInteger('refperiode_id')->nullable();
            $table->text('tujuan_p_isaktif')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sakip_tujuan_p');
    }
};