<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sakip_tujuan_pakdhe', function (Blueprint $table) {
            $table->text('id_detail_renstra')->primary();
            $table->text('id_unit')->nullable();
            $table->text('kode_urut')->nullable();
            $table->text('uraian')->nullable();
            $table->integer('tahun')->nullable();
            $table->text('sasaran_strategis')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sakip_tujuan_pakdhe');
    }
};