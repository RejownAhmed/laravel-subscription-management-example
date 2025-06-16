<?php

namespace App\Models\Subscription;

use App\Models\Subscription\Plan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Module extends Model
{
    use HasFactory;
    protected $fillable = ["name", "group_name", "description"];
    protected $hidden = ['pivot']; // Hide pivot relation

    public function plans(){
        return $this->belongsToMany(Plan::class);

    }
    
}
