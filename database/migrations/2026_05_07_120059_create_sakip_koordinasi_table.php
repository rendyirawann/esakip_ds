<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sakip_koordinasi', function (Blueprint $table) {
            $table->bigIncrements('refkoordinasi_id');
            $table->bigInteger('refuser_id')->nullable();
            $table->bigInteger('refskpd_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sakip_koordinasi');
    }
};