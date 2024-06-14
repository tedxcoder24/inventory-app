<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConfigResource extends JsonResource
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
            'last_serial_number' => $this->last_serial_number,
            'serial_port' => $this->serial_port,
            'label_printer' => $this->label_printer,
            'report_printer' => $this->report_printer,
            'image_directory'=> $this->image_directory,
            'baud_rate' => $this->baud_rate,
            'data_bits' => $this->data_bits,
            'stop_bits' => $this->stop_bits,
            'parity' => $this->parity,
        ];
    }
}
