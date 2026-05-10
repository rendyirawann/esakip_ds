<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SakipSasaranrenstra extends Model
{
    use HasFactory;

    protected $table = 'sakip_sasaranrenstra';
    protected $primaryKey = 'refsasaranrenstra_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $guarded = [];

    public function skpd()
    {
        return $this->belongsTo(SakipSkpd::class, 'refskpd_id', 'refskpd_id');
    }

    public function visi()
    {
        return $this->belongsTo(SakipVisi::class, 'refvisi_id', 'refvisi_id');
    }

    public function misi()
    {
        return $this->belongsTo(SakipMisi::class, 'refmisi_id', 'refmisi_id');
    }

    public function periode()
    {
        return $this->belongsTo(SakipPeriode::class, 'refperiode_id', 'refperiode_id');
    }

    public function sasaranRpjmd()
    {
        return $this->belongsTo(SakipSasaran::class, 'refsasaran_id', 'refsasaran_id');
    }

    public function tujuanRpjmd()
    {
        return $this->belongsTo(SakipTujuan::class, 'reftujuan_id', 'reftujuan_id');
    }

    public function tujuanRenstra()
    {
        return $this->hasMany(SakipTujuanrenstra::class, 'refsasaranrenstra_id', 'refsasaranrenstra_id');
    }

    public function linkedTujuanRenstra()
    {
        return $this->belongsTo(SakipTujuanrenstra::class, 'reftujuanrenstra_id', 'reftujuanrenstra_id');
    }

    public function indikators()
    {
        return $this->hasMany(SakipIndikatorsasaranrenstra::class, 'refsasaranrenstra_id', 'refsasaranrenstra_id');
    }
}
