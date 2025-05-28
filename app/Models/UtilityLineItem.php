<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class UtilityLineItem extends Model
{
    protected $guarded = [];
    public function utility():BelongsTo
    {
        return $this->belongsTo(UtilityFacility::class, 'utility_id');
    }
    public function item():BelongsTo
    {
        return $this->belongsTo(UtilityItem::class, 'item_id');
    }
}
