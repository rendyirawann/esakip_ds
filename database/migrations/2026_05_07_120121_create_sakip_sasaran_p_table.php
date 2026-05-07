<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sakip_sasaran_p', function (Blueprint $table) {
            $table->bigIncrements('refsasaran_p_id');
            $table->text('uraian_sasaran_p');
            $table->bigInteger('refperiode_id')->nullable();
            $table->bigInteger('refvisi_p_id')->nullable();
            $table->bigInteger('refmisi_p_id')->nullable();
            $table->bigInteger('reftujuan_p_id')->nullable();
            $table->text('sasaran_p_isaktif')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sakip_sasaran_p');
    }
};