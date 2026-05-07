<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sakip_kegiatan', function (Blueprint $table) {
            $table->bigIncrements('refkegiatan_id');
            $table->text('kode_kegiatan');
            $table->text('nama_kegiatan');
            $table->bigInteger('refurusan_id')->nullable();
            $table->bigInteger('refbidang_id')->nullable();
            $table->bigInteger('refprogram_id')->nullable();
            $table->text('kegiatan_isaktif')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sakip_kegiatan');
    }
};