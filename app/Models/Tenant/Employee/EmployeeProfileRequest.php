<?php

namespace App\Models\Tenant\Employee;

use App\Models\Tenant\TenantBaseModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Models\EmployeeProfile\EmployeeProfile;
use App\Models\Concerns\Relationship\StatusRelationTrait;

class EmployeeProfileRequest extends TenantBaseModel
{
    use StatusRelationTrait;

    protected $fillable = [
        'name',
        'ar_name',
        'phone_country',
        'phone_number',
        'email',
        'designation',
        'ar_designation',
        'department_id',
        'employee_profile_id',
        'status_id',
        'tenant_id',
        'request_by',
        'previous',
        'note'
    ];

    protected $casts = [
        'employee_profile_id' => 'integer',
        'status_id' => 'integer',
        'tenant_id' => 'integer',
        'request_by' => 'integer',
    ];

    public function employeeProfile()
    {
        return $this->belongsTo(EmployeeProfile::class, 'employee_profile_id');
    }

    public function requestedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'request_by');
    }
}
