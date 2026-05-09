<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SakipPimpinan extends Model
{
    use HasFactory;

    protected $table = 'sakip_pimpinan';
    protected $primaryKey = 'refpimpinan_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $guarded = [];

    public function periode()
    {
        return $this->belongsTo(SakipPeriode::class, 'refperiode_id', 'refperiode_id');
    }

}
