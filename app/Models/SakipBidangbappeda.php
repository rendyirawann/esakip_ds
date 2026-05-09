<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SakipBidangbappeda extends Model
{
    use HasFactory;

    protected $table = 'sakip_bidangbappeda';
    protected $primaryKey = 'refbidangbappeda_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $guarded = [];

}
