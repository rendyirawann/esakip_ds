<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sakip_lke', function (Blueprint $table) {
            $table->bigIncrements('reflke_id');
            $table->bigInteger('refperiode_id')->nullable();
            $table->bigInteger('refskpd_id')->nullable();
            $table->bigInteger('reflkekomponen_id')->nullable();
            $table->bigInteger('reflkesubkomponen_id')->nullable();
            $table->text('unit_jawaban')->nullable();
            $table->text('unit_nilai')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sakip_lke');
    }
};