<?php

namespace App\Http\Resources;

use App\Models\WeightUnit;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'value' => $this->id,
            'text' => $this->item_type,
            'weight_unit' => WeightUnit::where('id', $this->weight_unit_id)->first(),
            'enabled' => $this->enabled,
        ];
    }
}
