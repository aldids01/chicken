<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class HygieneItem extends Model
{
    protected $guarded = [];
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id');
    }
    public function hygiene(): BelongsTo
    {
        return $this->belongsTo(HygieneClean::class, 'hygiene_id');
    }
}
