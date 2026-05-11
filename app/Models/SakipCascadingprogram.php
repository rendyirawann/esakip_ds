<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SakipCascadingprogram extends Model
{
    use HasFactory;

    protected $table = 'sakip_cascadingprogram';
    protected $primaryKey = 'refcascadingprogram_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $guarded = [];

    public function skpd()
    {
        return $this->belongsTo(SakipSkpd::class, 'refskpd_id', 'refskpd_id');
    }

    public function misi()
    {
        return $this->belongsTo(SakipMisi::class, 'refmisi_id', 'refmisi_id');
    }

    public function bidang()
    {
        return $this->belongsTo(SakipBidang::class, 'refbidang_id', 'refbidang_id');
    }

    public function program()
    {
        return $this->belongsTo(SakipProgram::class, 'refprogram_id', 'refprogram_id');
    }

    public function periode()
    {
        return $this->belongsTo(SakipPeriode::class, 'refperiode_id', 'refperiode_id');
    }

    public function sasaranRenstra()
    {
        return $this->belongsTo(SakipSasaranrenstra::class, 'refsasaranrenstra_id', 'refsasaranrenstra_id');
    }

    public function tujuanRpjmd()
    {
        return $this->belongsTo(SakipTujuan::class, 'reftujuan_id', 'reftujuan_id');
    }

    public function penjabat()
    {
        return $this->hasMany(SakipPenjabatskpdCascadingprogram::class, 'refcascadingprogram_id', 'refcascadingprogram_id');
    }
}
