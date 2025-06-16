<?php

namespace App\Models\Subscription\Concerns;

use App\Enums\Subscription\TaxType;
use App\Models\Subscription\Tax\Tax;

trait HasTax
{
    public function hasTax() {
        return !!$this->tax_id;

    }
    public function taxAmount() {
        if(!$this->hasTax()) return 0;

        // By default if fixed, amount is fixed
        $amount = $this->tax->amount;
        // If percentage calculate the amount
        if ($this->tax->type === TaxType::PERCENTAGE) {
            $amount = ($this->price * $this->tax->amount) / 100;

        }

        return $amount;

    }
    public function totalPriceWithTax() {
        return $this->price + $this->taxAmount();

    }

    public function tax()
    {
        return $this->belongsTo(Tax::class);

    }
}
