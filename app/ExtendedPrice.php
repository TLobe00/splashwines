<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExtendedPrice extends Model
{
    public $incrementing = false;
    protected $table = 'ExtendedPrice';
    protected $primaryKey = ['id'];
}
