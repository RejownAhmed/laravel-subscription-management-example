<?php

namespace App\Models\Tenant;

use App\Models\Concerns\HasFilter;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\Public\CanBePublic;
use App\Models\Concerns\Relationship\BelongToTenant;

class TenantBaseModel extends Model
{
    use BelongToTenant, CanBePublic, HasFilter;
}
