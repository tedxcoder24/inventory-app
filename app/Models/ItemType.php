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
        'weight_unit_id',
        'enabled'
    ];

    public function weightUnit()
    {
        return $this->belongsTo(WeightUnit::class);
    }
}
