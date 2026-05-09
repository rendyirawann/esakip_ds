<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SakipSasaran extends Model
{
    use HasFactory;

    protected $table = 'sakip_sasaran';
    protected $primaryKey = 'refsasaran_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $guarded = [];

    public function periode()
    {
        return $this->belongsTo(SakipPeriode::class, 'refperiode_id', 'refperiode_id');
    }

    public function visi()
    {
        return $this->belongsTo(SakipVisi::class, 'refvisi_id', 'refvisi_id');
    }

    public function misi()
    {
        return $this->belongsTo(SakipMisi::class, 'refmisi_id', 'refmisi_id');
    }

}
