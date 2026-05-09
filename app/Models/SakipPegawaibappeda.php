<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SakipPegawaibappeda extends Model
{
    use HasFactory;

    protected $table = 'sakip_pegawaibappeda';
    protected $primaryKey = 'refpegawai_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $guarded = [];
    
    public function title()
    {
        return $this->belongsTo(SakipTitle::class, 'reftitle_id', 'reftitle_id');
    }

}
