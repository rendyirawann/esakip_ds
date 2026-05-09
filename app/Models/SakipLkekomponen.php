<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SakipLkekomponen extends Model
{
    use HasFactory;

    protected $table = 'sakip_lkekomponen';
    protected $primaryKey = 'reflkekomponen_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $guarded = [];

}
