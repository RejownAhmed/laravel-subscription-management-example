<?php

namespace App\Models\Subscription;

use App\Enums\Subscription\TaxType;
use App\Models\Subscription\Plan;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
   protected $fillable = ['type', 'name', 'amount'];

    protected $casts = [
        "type" => TaxType::class,
    ];

   public function plans() {
    return $this->hasMany(Plan::class);

   }

}
