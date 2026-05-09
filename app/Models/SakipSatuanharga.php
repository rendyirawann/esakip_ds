<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SakipSatuanharga extends Model
{
    use HasFactory;

    protected $table = 'sakip_satuanharga';
    protected $primaryKey = 'refsatuanharga_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $guarded = [];

}
