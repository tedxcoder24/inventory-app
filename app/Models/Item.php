<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'operator_id',
        'date_time',
        'serial_number',
        'item_type_id',
        'batch_id',
        'metrc_id',
        'tare_weight',
        'gross_weight',
        'weight_unit_id',
        'strain_id',
        'product_id',
        'color_id',
        'clarity_id',
        'appearance_id'
    ];

    public function weights()
    {
        return $this->morphMany(ChangeItemWeight::class, 'changeable');
    }

    public function statuses()
    {
        return $this->morphMany(ChangeItemStatus::class, 'changeable');
    }

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    public function itemType()
    {
        return $this->belongsTo(ItemType::class);
    }

    public function strain()
    {
        return $this->belongsTo(Strain::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function clarity()
    {
        return $this->belongsTo(Clarity::class);
    }

    public function appearance()
    {
        return $this->belongsTo(Appearance::class);
    }
}
