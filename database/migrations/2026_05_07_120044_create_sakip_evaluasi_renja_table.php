<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sakip_evaluasi_renja', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('refskpd_id')->nullable();
            $table->integer('tahun');
            $table->integer('no_urut')->nullable();
            $table->integer('row_order');
            $table->string('level')->nullable();
            $table->text('nama_unsur')->nullable();
            $table->text('nama_bidang_urusan')->nullable();
            $table->text('nama_program')->nullable();
            $table->text('nama_kegiatan')->nullable();
            $table->text('nama_sub_kegiatan')->nullable();
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();
            $table->text('tujuan')->nullable();
            $table->text('sasaran_strategis')->nullable();
            $table->text('sasaran_opd')->nullable();
            $table->text('indikator_kinerja')->nullable();
            $table->text('satuan')->nullable();
            $table->decimal('target_renstra_kinerja', 20, 2)->nullable();
            $table->decimal('target_renstra_anggaran', 20, 2)->nullable();
            $table->decimal('realisasi_sd_lalu_kinerja', 20, 2)->nullable();
            $table->decimal('realisasi_sd_lalu_anggaran', 20, 2)->nullable();
            $table->decimal('target_renja_kinerja', 20, 2)->nullable();
            $table->decimal('target_renja_anggaran', 20, 2)->nullable();
            $table->decimal('tw1_kinerja', 20, 2)->nullable();
            $table->decimal('tw1_persen', 20, 2)->nullable();
            $table->decimal('tw1_anggaran', 20, 2)->nullable();
            $table->decimal('tw2_kinerja', 20, 2)->nullable();
            $table->decimal('tw2_persen', 20, 2)->nullable();
            $table->decimal('tw2_anggaran', 20, 2)->nullable();
            $table->decimal('tw3_kinerja', 20, 2)->nullable();
            $table->decimal('tw3_persen', 20, 2)->nullable();
            $table->decimal('tw3_anggaran', 20, 2)->nullable();
            $table->decimal('tw4_kinerja', 20, 2)->nullable();
            $table->decimal('tw4_persen', 20, 2)->nullable();
            $table->decimal('tw4_anggaran', 20, 2)->nullable();
            $table->decimal('realisasi_capaian_kinerja', 20, 2)->nullable();
            $table->decimal('realisasi_capaian_anggaran', 20, 2)->nullable();
            $table->decimal('realisasi_renstra_kinerja', 20, 2)->nullable();
            $table->decimal('realisasi_renstra_anggaran', 20, 2)->nullable();
            $table->decimal('tingkat_capaian_kinerja', 20, 2)->nullable();
            $table->decimal('tingkat_capaian_anggaran', 20, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sakip_evaluasi_renja');
    }
};