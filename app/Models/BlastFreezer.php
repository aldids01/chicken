<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlastFreezer extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    public function package(): BelongsTo
    {
        return $this->belongsTo(PackageType::class);
    }
    public function handleBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handle_by_id');
    }
}
