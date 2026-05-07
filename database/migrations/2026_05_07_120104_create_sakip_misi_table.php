<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sakip_misi', function (Blueprint $table) {
            $table->bigIncrements('refmisi_id');
            $table->text('uraian_misi');
            $table->bigInteger('refperiode_id')->nullable();
            $table->bigInteger('refvisi_id')->nullable();
            $table->text('misi_isaktif')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sakip_misi');
    }
};