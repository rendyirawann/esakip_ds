<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SakipTitle extends Model
{
    use HasFactory;

    protected $table = 'sakip_title';
    protected $primaryKey = 'reftitle_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $guarded = [];

}
