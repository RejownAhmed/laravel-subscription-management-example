<?php

namespace App\Models\Concerns\Relationship;

use App\Models\User;

trait HasCreator
{
    public static function bootHasCreator(): void
    {
        if (!app()->runningInConsole()) {
            static::creating(function ($model) {
                if (!$model->created_by) {
                    $model->created_by = auth()->id();

                }

            });

            static::saving(function ($model) {
                if (!$model->created_by) {
                    $model->created_by = auth()->id();

                }

            });
        }
    }

    // Created By
    public function createdBy()
    {
        return $this->belongsTo(User::class, "created_by", 'id');

    }

}
