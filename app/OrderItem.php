<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    public $incrementing = false;
    protected $table = 'orderlineitems';
    protected $primaryKey = ['id'];
}
