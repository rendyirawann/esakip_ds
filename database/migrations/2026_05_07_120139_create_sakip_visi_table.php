<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sakip_visi', function (Blueprint $table) {
            $table->bigIncrements('refvisi_id');
            $table->text('uraian_visi');
            $table->text('penjabaran_visi');
            $table->bigInteger('refperiode_id')->nullable();
            $table->text('visi_isaktif')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sakip_visi');
    }
};