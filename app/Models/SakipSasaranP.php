<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SakipSasaranP extends Model
{
    use HasFactory;

    protected $table = 'sakip_sasaran_p';
    protected $primaryKey = 'refsasaran_p_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $guarded = [];

    public function periode()
    {
        return $this->belongsTo(SakipPeriode::class, 'refperiode_id', 'refperiode_id');
    }

}
