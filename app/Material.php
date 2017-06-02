<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $guarded = [];
    protected $table = 'materials';
    public $timestamps  = false;
}
