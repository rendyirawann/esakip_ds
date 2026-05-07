<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sakip_lkesubkomponen', function (Blueprint $table) {
            $table->bigIncrements('reflkesubkomponen_id');
            $table->bigInteger('reflkekomponen_id')->nullable();
            $table->text('uraian_lkesubkomponen')->nullable();
            $table->text('bobot_lkesubkomponen')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sakip_lkesubkomponen');
    }
};