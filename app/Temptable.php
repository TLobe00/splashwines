<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Temptable extends Model
{
    //
    public $incrementing = false;
    protected $table = 'temptable';
    protected $primaryKey = 'id';
}
