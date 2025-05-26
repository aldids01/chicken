<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialInventoryCheck extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    public function supervisor():BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }
    public function items(): HasMany
    {
        return $this->hasMany(LineItem::class, 'material_id');
    }
}
