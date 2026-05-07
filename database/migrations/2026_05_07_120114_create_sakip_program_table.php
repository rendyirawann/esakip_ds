<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sakip_program', function (Blueprint $table) {
            $table->bigIncrements('refprogram_id');
            $table->text('kode_program');
            $table->text('nama_program');
            $table->bigInteger('refurusan_id')->nullable();
            $table->bigInteger('refbidang_id')->nullable();
            $table->text('program_isaktif')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sakip_program');
    }
};