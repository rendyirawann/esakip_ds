<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SakipPeriode extends Model
{
    use HasFactory;

    protected $table = 'sakip_periode';
    protected $primaryKey = 'refperiode_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $guarded = [];

}
