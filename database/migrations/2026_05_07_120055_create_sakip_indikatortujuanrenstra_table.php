<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sakip_indikatortujuanrenstra', function (Blueprint $table) {
            $table->bigIncrements('refindikatortujuanrenstra_id');
            $table->text('uraian_indikatortujuanrenstra');
            $table->bigInteger('reftujuanrenstra_id')->nullable();
            $table->bigInteger('refsasaranrenstra_id')->nullable();
            $table->bigInteger('refskpd_id')->nullable();
            $table->bigInteger('refperiode_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sakip_indikatortujuanrenstra');
    }
};