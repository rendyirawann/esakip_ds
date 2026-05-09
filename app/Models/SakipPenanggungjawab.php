<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SakipPenanggungjawab extends Model
{
    use HasFactory;

    protected $table = 'sakip_penanggungjawab';
    protected $primaryKey = 'refpenanggungjawab_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $guarded = [];

    public function skpd()
    {
        return $this->belongsTo(SakipSkpd::class, 'refskpd_id', 'refskpd_id');
    }

}
