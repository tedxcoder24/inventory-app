<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemChange extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_time',
        'operator_id',
        'item_id',
        'status_id',
        'gross_weight',
        'note',
    ];

    public function operator()
    {
        return $this->belongsTo(Operator::class, 'operator_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class,'item_id');
    }
}
