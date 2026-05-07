<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sakip_penjabat_skpd', function (Blueprint $table) {
            $table->bigIncrements('refpenjabatskpd_id');
            $table->bigInteger('refskpd_id')->nullable();
            $table->bigInteger('refperiode_id')->nullable();
            $table->text('nama_penjabat')->nullable();
            $table->text('nip_penjabat')->nullable();
            $table->text('jabatan_eselon')->nullable();
            $table->text('pangkat_eselon')->nullable();
            $table->bigInteger('refeselon_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sakip_penjabat_skpd');
    }
};