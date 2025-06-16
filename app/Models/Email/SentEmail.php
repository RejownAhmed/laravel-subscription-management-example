<?php

namespace App\Models\Email;

use App\Models\Tenant\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class SentEmail extends Model
{
    protected $fillable = ["tenant_id", "user_id", "type", "meta"];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
