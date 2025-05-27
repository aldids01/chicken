<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Processing extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    public function packageSupervisor():BelongsTo
    {
        return $this->belongsTo(User::class, 'package_supervisor_id');
    }
    public function packageOfficer():BelongsTo
    {
        return $this->belongsTo(User::class, 'package_officer_id');
    }
    public function package(): BelongsTo
    {
        return $this->belongsTo(PackageType::class);
    }
}
