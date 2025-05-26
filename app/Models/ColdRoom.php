<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ColdRoom extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    public function package(): BelongsTo
    {
        return $this->belongsTo(PackageType::class);
    }
    public function transferredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'transferred_by_id');
    }
}
