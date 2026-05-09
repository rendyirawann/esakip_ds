<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SakipMisi extends Model
{
    use HasFactory;

    protected $table = 'sakip_misi';
    protected $primaryKey = 'refmisi_id';
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

}
