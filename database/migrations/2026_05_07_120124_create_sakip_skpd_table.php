<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sakip_skpd', function (Blueprint $table) {
            $table->bigIncrements('refskpd_id');
            $table->text('kode_skpd');
            $table->text('nama_skpd');
            $table->text('kepala_skpd');
            $table->text('nip_kepala');
            $table->text('jabatan_kepala');
            $table->text('pangkat_kepala');
            $table->bigInteger('refurusan_id')->nullable();
            $table->bigInteger('refbidang_id')->nullable();
            $table->text('refskpd_unit')->nullable();
            $table->text('refskpd_keterangan')->nullable();
            $table->text('skpd_isaktif')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sakip_skpd');
    }
};