<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    public function lineItems(): HasMany
    {
        return $this->hasMany(LineItem::class, 'item_id');
    }
}
