<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SakipKoordinasi extends Model
{
    use HasFactory;

    protected $table = 'sakip_koordinasi';
    protected $primaryKey = 'refkoordinasi_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $guarded = [];

    public function skpd()
    {
        return $this->belongsTo(SakipSkpd::class, 'refskpd_id', 'refskpd_id');
    }

}
