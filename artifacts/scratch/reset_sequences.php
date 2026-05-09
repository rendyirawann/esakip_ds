<?php
use Illuminate\Support\Facades\DB;

$tables = [
    'sakip_periode' => 'refperiode_id',
    'sakip_visi' => 'refvisi_id',
    'sakip_misi' => 'refmisi_id',
    'sakip_urusan' => 'urusan_id',
    'sakip_bidang' => 'refbidang_id',
    'sakip_program' => 'refprogram_id',
    'sakip_kegiatan' => 'refkegiatan_id',
    'sakip_subkegiatan' => 'refsubkegiatan_id',
    'sakip_skpd' => 'refskpd_id',
    'sakip_penjabat_skpd' => 'refpenjabatskpd_id',
    'sakip_pimpinan' => 'refpimpinan_id',
    'sakip_subunit' => 'refsubunit_id',
    'sakip_title' => 'reftitle_id',
    'sakip_unitkerja' => 'id',
    'sakip_pegawaibappeda' => 'refpegawai_id'
];

foreach ($tables as $table => $id) {
    try {
        $max = DB::table($table)->max($id);
        if ($max) {
            $seq = $table . '_' . $id . '_seq';
            DB::statement("SELECT setval('$seq', $max)");
            echo "Reset $seq to $max\n";
        }
    } catch (\Exception $e) {
        echo "Failed for $table: " . $e->getMessage() . "\n";
    }
}
