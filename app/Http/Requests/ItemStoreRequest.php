<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemStoreRequest extends FormRequest
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
            'operator_id' => 'required|exists:operators,id',
            'date_time' => 'required|date',
            'item_type_id' => 'required|exists:item_types,id',
            'batch_id'=> 'nullable|string|max:25',
            'metrc_id'=> 'nullable|string|max:25',
            'tare_weight'=> 'required|numeric|min:0',
            'gross_weight'=> 'required|numeric|min:0',
            'strain_id'=> 'required|exists:strains,id',
            'product_id'=> 'required|exists:products,id',
            'color_id'=> 'nullable|exists:colors,id',
            'clarity_id'=> 'nullable|exists:clarities,id',
            'appearance_id' => 'nullable|exists:appearances,id',
            'note' => 'nullable|string|max:255',
            'status_id' => 'exists:statuses,id',
        ];
    }
}
