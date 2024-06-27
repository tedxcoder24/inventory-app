<?php

namespace App\Http\Resources;

use App\Models\Appearance;
use App\Models\Clarity;
use App\Models\Color;
use App\Models\Item;
use App\Models\Operator;
use App\Models\ItemType;
use App\Models\Product;
use App\Models\Status;
use App\Models\Strain;
use App\Models\WeightUnit;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'operator' => Operator::where('id', $this->operator_id)->first()->operator,
            'status' => Item::findOrFail($this->id)->statuses()->latest()->first()->status->status,
            'date_time' => $this->date_time,
            'serial_number' => $this->serial_number,
            'item_type' => ItemType::where('id', $this->item_type_id)->first()->item_type,
            'weight_unit' => ItemType::where('id', $this->item_type_id)->first()->weightUnit->abbreviation,
            'convert_to_grams' => ItemType::where('id', $this->item_type_id)->first()->weightUnit->convert_to_grams,
            'batch_id' => $this->batch_id,
            'metrc_id' => $this->metrc_id,
            'tare_weight' => $this->tare_weight,
            'gross_weight' => $this->gross_weight,
            'strain' => Strain::where('id', $this->strain_id)->first()->strain,
            'product' => Product::where('id', $this->product_id)->first()->product,
            'color' => Color::where('id', $this->color_id)->first()->color,
            'clarity' => Clarity::where('id', $this->clarity_id)->first()->clarity,
            'appearance' => Appearance::where('id', $this->appearance_id)->first()->appearance,
        ];
    }
}
