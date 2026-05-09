<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SakipEselon extends Model
{
    use HasFactory;

    protected $table = 'sakip_eselon';
    protected $primaryKey = 'refeselon_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $guarded = [];

}
