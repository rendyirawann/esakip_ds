<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SakipKegiatanPakdhe extends Model
{
    use HasFactory;

    protected $table = 'sakip_kegiatan_pakdhe';
    protected $primaryKey = 'id_detail_renstra';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];

}
