<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfigStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'last_serial_number' => 'required|digits:9|numeric',
            'serial_port' => 'required|string|max:100',
            'label_printer' => 'required|string|max:100',
            'report_printer' => 'required|string|max:100',
            'image_directory' => 'required|string|max:250',
            'baud_rate' => 'required|in:9600,19200,38400,57600,115200',
            'data_bits' => 'required|in:7,8',
            'stop_bits' => 'required|in:1,2',
            'parity' => 'required|in:none,even,odd',
        ];
    }
}
