<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sakip_tujuanrenstra', function (Blueprint $table) {
            $table->bigIncrements('reftujuanrenstra_id');
            $table->text('uraian_tujuanrenstra');
            $table->bigInteger('refskpd_id')->nullable();
            $table->bigInteger('refmisi_id')->nullable();
            $table->bigInteger('reftujuan_id')->nullable();
            $table->bigInteger('refsasaranrenstra_id')->nullable();
            $table->bigInteger('refsasaran_id')->nullable();
            $table->bigInteger('refperiode_id')->nullable();
            $table->text('user_create')->nullable();
            $table->text('date_create')->nullable();
            $table->text('user_edit')->nullable();
            $table->text('date_edit')->nullable();
            $table->text('user_delete')->nullable();
            $table->text('date_delete')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sakip_tujuanrenstra');
    }
};