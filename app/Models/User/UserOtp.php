<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOtp extends Model
{
    use HasFactory;
    protected $fillable = [
        'phone_number',
        'otp',
        'expire_at',
        'verified_at',
    ];

    // No need timestamps field

    public $timestamps = false;

    public static function validate($phone_number): bool {
       $verifiedOtp = static::
            where('phone_number', $phone_number)
            ->whereNotNull('verified_at')
            ->first();

       if (!$verifiedOtp) {
        return false;
       }

        // Delete the verified otp entry if successfully validated
        return $verifiedOtp->delete();

    }
}
