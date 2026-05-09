<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SakipProgramPakdhe extends Model
{
    use HasFactory;

    protected $table = 'sakip_program_pakdhe';
    protected $primaryKey = 'id_detail_renstra';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $guarded = [];

}
