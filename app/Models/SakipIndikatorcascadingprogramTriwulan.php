<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SakipIndikatorcascadingprogramTriwulan extends Model
{
    use HasFactory;

    protected $table = 'sakip_indikatorcascadingprogram_triwulan';
    protected $primaryKey = 'refindikatorprogramtriwulan_id';
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

    public function bidang()
    {
        return $this->belongsTo(SakipBidang::class, 'refbidang_id', 'refbidang_id');
    }

    public function program()
    {
        return $this->belongsTo(SakipProgram::class, 'refprogram_id', 'refprogram_id');
    }

    public function indikator()
    {
        return $this->belongsTo(SakipIndikatorcascadingprogram::class, 'refindikatorprogram_id', 'refindikatorprogram_id');
    }

}
