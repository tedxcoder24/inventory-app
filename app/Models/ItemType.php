<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'item_type', 
        'enabled'
    ];

    public function weightUnit()
    {
        $this->belongsTo(WeightUnit::class);
    }
}
