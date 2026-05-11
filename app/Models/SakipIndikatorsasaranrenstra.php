<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SakipIndikatorsasaranrenstra extends Model
{
    use HasFactory;

    protected $table = 'sakip_indikatorsasaranrenstra';
    protected $primaryKey = 'refindikatorsasaranrenstra_id';
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

    public function triwulan()
    {
        return $this->hasMany(SakipIndikatorsasaranrenstraTriwulan::class, 'refindikatorsasaranrenstra_id', 'refindikatorsasaranrenstra_id');
    }

    public function sasaran()
    {
        return $this->belongsTo(SakipSasaranrenstra::class, 'refsasaranrenstra_id', 'refsasaranrenstra_id');
    }
}
