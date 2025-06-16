<?php

namespace App\Models\Status;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $fillable = ['name', 'type', 'class'];

    public static function findByNameAndType($name, $type = 'user')
    {
        return self::query()
            ->where('name', $name)
            ->where('type', $type)
            ->first();
    }
}
