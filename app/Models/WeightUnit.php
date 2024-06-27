<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WeightUnit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['weight_unit', 'abbreviation', 'convert_to_grams'];
}
