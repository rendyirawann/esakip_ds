<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SakipLkesubkomponen extends Model
{
    use HasFactory;

    protected $table = 'sakip_lkesubkomponen';
    protected $primaryKey = 'reflkesubkomponen_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $guarded = [];

}
