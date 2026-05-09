<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SakipBidang extends Model
{
    use HasFactory;

    protected $table = 'sakip_bidang';
    protected $primaryKey = 'refbidang_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $guarded = [];

    public function urusan()
    {
        return $this->belongsTo(SakipUrusan::class, 'refurusan_id', 'urusan_id');
    }

    public function programs()
    {
        return $this->hasMany(SakipCascadingProgram::class, 'refbidang_id', 'refbidang_id');
    }

}
