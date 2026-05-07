<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sakip_sasaran', function (Blueprint $table) {
            $table->bigIncrements('refsasaran_id');
            $table->text('uraian_sasaran');
            $table->bigInteger('refperiode_id')->nullable();
            $table->bigInteger('refvisi_id')->nullable();
            $table->bigInteger('refmisi_id')->nullable();
            $table->bigInteger('reftujuan_id')->nullable();
            $table->text('sasaran_isaktif')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sakip_sasaran');
    }
};