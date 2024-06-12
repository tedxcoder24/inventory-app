<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChangeItemStatus extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [        
        'date_time',
        'operator_id',
        'changeable_id',
        'changeable_type',
        'status_id',
        'note'
    ];

    public function changeable()
    {
        return $this->morphTo();
    }

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
