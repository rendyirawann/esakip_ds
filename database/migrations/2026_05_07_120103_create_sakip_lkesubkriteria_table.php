<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sakip_lkesubkriteria', function (Blueprint $table) {
            $table->bigIncrements('reflkesubkriteria_id');
            $table->bigInteger('reflkekomponen_id')->nullable();
            $table->bigInteger('reflkesubkomponen_id')->nullable();
            $table->text('uraian_lkesubkriteria')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sakip_lkesubkriteria');
    }
};