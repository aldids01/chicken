<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UtilityItem extends Model
{
    use SoftDeletes;
    protected $guarded = [];

}
