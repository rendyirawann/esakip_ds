<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sakip_misi_p', function (Blueprint $table) {
            $table->bigIncrements('refmisi_p_id');
            $table->text('uraian_misi_p');
            $table->bigInteger('refperiode_id')->nullable();
            $table->bigInteger('refvisi_p_id')->nullable();
            $table->text('misi_p_isaktif')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sakip_misi_p');
    }
};