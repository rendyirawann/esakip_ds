<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sakip_bidang', function (Blueprint $table) {
            $table->bigIncrements('refbidang_id');
            $table->text('kode_bidang');
            $table->text('nama_bidang');
            $table->text('bidang_isaktif')->nullable();
            $table->bigInteger('refurusan_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sakip_bidang');
    }
};