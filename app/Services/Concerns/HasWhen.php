<?php

namespace App\Services\Concerns;

trait HasWhen
{
    public function when($value, callable $callback, callable $fallback = null)
    {
        if ($value) {
            return $callback($this, $value) ?: $this;
        } elseif ($fallback) {
            return $fallback($this, $value) ?: $this;
        }

        return $this;
    }
}
