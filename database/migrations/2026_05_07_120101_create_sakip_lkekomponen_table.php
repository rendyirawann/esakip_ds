<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sakip_lkekomponen', function (Blueprint $table) {
            $table->bigIncrements('reflkekomponen_id');
            $table->text('uraian_lkekomponen')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sakip_lkekomponen');
    }
};