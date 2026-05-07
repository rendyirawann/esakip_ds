<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sakip_visi_p', function (Blueprint $table) {
            $table->bigIncrements('refvisi_p_id');
            $table->text('uraian_visi_p');
            $table->text('penjabaran_visi_p');
            $table->bigInteger('refperiode_id')->nullable();
            $table->text('visi_p_isaktif')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sakip_visi_p');
    }
};