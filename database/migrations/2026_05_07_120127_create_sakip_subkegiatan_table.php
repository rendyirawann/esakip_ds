<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sakip_subkegiatan', function (Blueprint $table) {
            $table->bigIncrements('refsubkegiatan_id');
            $table->text('kode_subkegiatan');
            $table->text('nama_subkegiatan');
            $table->bigInteger('refurusan_id')->nullable();
            $table->bigInteger('refbidang_id')->nullable();
            $table->bigInteger('refprogram_id')->nullable();
            $table->bigInteger('refkegiatan_id')->nullable();
            $table->text('subkegiatan_isaktif')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sakip_subkegiatan');
    }
};