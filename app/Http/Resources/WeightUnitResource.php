<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WeightUnitResource extends JsonResource
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
            'text' => $this->weight_unit,
            'abbreviation' => $this->abbreviation,
            'convert_to_grams' => $this->convert_to_grams,
            'enabled' => $this->enabled,
        ];
    }
}
