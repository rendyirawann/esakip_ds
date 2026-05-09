<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SakipSkpdcopyy extends Model
{
    use HasFactory;

    protected $table = 'sakip_skpdcopyy';
    protected $primaryKey = 'refskpd_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $guarded = [];

    public function urusan()
    {
        return $this->belongsTo(SakipUrusan::class, 'refurusan_id', 'refurusan_id');
    }

    public function bidang()
    {
        return $this->belongsTo(SakipBidang::class, 'refbidang_id', 'refbidang_id');
    }

}
