<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SakipPenjabatSkpd extends Model
{
    use HasFactory;

    protected $table = 'sakip_penjabat_skpd';
    protected $primaryKey = 'refpenjabatskpd_id';
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

}
