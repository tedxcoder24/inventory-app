<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_time',
        'operator_id',
        'item_id',
        'status_id',
        'note',
    ];

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
