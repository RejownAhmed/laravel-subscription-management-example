<?php

namespace App\Models\Concerns\Relationship;

use App\Models\Status\Status;

trait HasStatus
{
    public function status(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
}
