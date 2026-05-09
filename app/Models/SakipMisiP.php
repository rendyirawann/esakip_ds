<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SakipMisiP extends Model
{
    use HasFactory;

    protected $table = 'sakip_misi_p';
    protected $primaryKey = 'refmisi_p_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $guarded = [];

    public function periode()
    {
        return $this->belongsTo(SakipPeriode::class, 'refperiode_id', 'refperiode_id');
    }

}
