<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SakipSubunit extends Model
{
    use HasFactory;

    protected $table = 'sakip_subunit';
    protected $primaryKey = 'refsubunit_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $guarded = [];

}
