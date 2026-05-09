<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SakipSubkegiatan extends Model
{
    use HasFactory;

    protected $table = 'sakip_subkegiatan';
    protected $primaryKey = 'refsubkegiatan_id';
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

    public function program()
    {
        return $this->belongsTo(SakipProgram::class, 'refprogram_id', 'refprogram_id');
    }

    public function kegiatan()
    {
        return $this->belongsTo(SakipKegiatan::class, 'refkegiatan_id', 'refkegiatan_id');
    }

}
