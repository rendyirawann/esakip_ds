<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SakipUrusan extends Model
{
    use HasFactory;

    protected $table = 'sakip_urusan';
    protected $primaryKey = 'urusan_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $guarded = [];

}
