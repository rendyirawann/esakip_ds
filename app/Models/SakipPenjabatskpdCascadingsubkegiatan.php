<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SakipPenjabatskpdCascadingsubkegiatan extends Model
{
    use HasFactory;

    protected $table = 'sakip_penjabatskpd_cascadingsubkegiatan';
    protected $primaryKey = 'refpenjabatcascadingsubkegiatan_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $guarded = [];

    public function skpd()
    {
        return $this->belongsTo(SakipSkpd::class, 'refskpd_id', 'refskpd_id');
    }

    public function periode()
    {
        return $this->belongsTo(SakipPeriode::class, 'refperiode_id', 'refperiode_id');
    }

    public function program()
    {
        return $this->belongsTo(SakipProgram::class, 'refprogram_id', 'refprogram_id');
    }

    public function kegiatan()
    {
        return $this->belongsTo(SakipKegiatan::class, 'refkegiatan_id', 'refkegiatan_id');
    }

    public function subkegiatan()
    {
        return $this->belongsTo(SakipSubkegiatan::class, 'refsubkegiatan_id', 'refsubkegiatan_id');
    }

    public function penjabatSkpd()
    {
        return $this->belongsTo(SakipPenjabatSkpd::class, 'refpenjabatskpd_id', 'refpenjabatskpd_id');
    }
}
