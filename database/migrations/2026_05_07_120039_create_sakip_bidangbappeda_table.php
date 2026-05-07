<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sakip_bidangbappeda', function (Blueprint $table) {
            $table->bigIncrements('refbidangbappeda_id');
            $table->text('nama_bidangbappeda')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sakip_bidangbappeda');
    }
};