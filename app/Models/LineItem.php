<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LineItem extends Model
{
    protected $guarded = [];
    public function material(): BelongsTo
    {
        return $this->belongsTo(MaterialInventoryCheck::class, 'material_id');
    }
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
