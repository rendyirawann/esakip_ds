<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SakipSkpd extends Model
{
    use HasFactory;

    protected $table = 'sakip_skpd';
    protected $primaryKey = 'refskpd_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $guarded = [];

    public function urusan()
    {
        return $this->belongsTo(SakipUrusan::class, 'refurusan_id', 'urusan_id');
    }

    public function bidang()
    {
        return $this->belongsTo(SakipBidang::class, 'refbidang_id', 'refbidang_id');
    }

    public function programs()
    {
        return $this->hasMany(SakipCascadingProgram::class, 'refskpd_id', 'refskpd_id');
    }

    public function kegiatans()
    {
        return $this->hasMany(SakipCascadingKegiatan::class, 'refskpd_id', 'refskpd_id');
    }

    public function subkegiatans()
    {
        return $this->hasMany(SakipCascadingSubkegiatan::class, 'refskpd_id', 'refskpd_id');
    }
}
