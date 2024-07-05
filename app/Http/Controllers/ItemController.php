<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemStoreRequest;
use App\Http\Resources\ItemResource;
use App\Http\Resources\OperatorResource;
use App\Http\Resources\ItemTypeResource;
use App\Http\Resources\WeightUnitResource;
use App\Http\Resources\StrainResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ColorResource;
use App\Http\Resources\ClarityResource;
use App\Http\Resources\AppearanceResource;
use App\Http\Resources\StatusResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

use App\Models\Item;
use App\Models\Operator;
use App\Models\ItemType;
use App\Models\WeightUnit;
use App\Models\Strain;
use App\Models\Product;
use App\Models\Color;
use App\Models\Clarity;
use App\Models\Appearance;
use App\Models\Status;
use App\Models\Config;
use App\Models\ItemStatus;
use App\Models\ItemWeight;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->input('query');
        if ($query) {
            $items = ItemResource::collection(Item::where('serial_number', 'like', '%' . $query . '%')->get());
        } else {
            $items = ItemResource::collection(Item::all());
        }

        return Inertia::render('Items/Index', [
            'items' => $items,
            'statuses' => StatusResource::collection(Status::all()),
            'operators' => OperatorResource::collection(Operator::all()),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Items/Create', [
            'operators' => OperatorResource::collection(Operator::where('enabled', true)->orderBy('operator')->get()),
            'itemTypes' => ItemTypeResource::collection(ItemType::where('enabled', true)->orderBy('item_type')->get()),
            'weightUnits' => WeightUnitResource::collection(WeightUnit::all()),
            'strains' => StrainResource::collection(Strain::where('enabled', true)->orderBy('strain')->get()),
            'products' => ProductResource::collection(Product::where('enabled', true)->orderBy('product')->get()),
            'colors' => ColorResource::collection(Color::where('enabled', true)->orderBy('color')->get()),
            'clarities' => ClarityResource::collection(Clarity::where('enabled', true)->orderBy('clarity')->get()),
            'appearances' => AppearanceResource::collection(Appearance::where('enabled', true)->orderBy('appearance')->get()),
            'statuses' => StatusResource::collection(Status::where('enabled', true)->orderBy('status')->get()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ItemStoreRequest $request)
    {
        $validated_data = $request->validated();

        $config = Config::first();
        $last_serial_number = $config->last_serial_number;
        $new_serial_number = str_pad((int)$last_serial_number + 1, 9, '0', STR_PAD_LEFT);

        // Save the new serial number back to the database
        $config->last_serial_number = $new_serial_number;
        $config->save();

        $formatted_date = Carbon::parse($validated_data['date_time'])->format('Y-m-d H:i:s');

        $item_data = [
            'operator_id' => $validated_data['operator_id'],
            'date_time' => $formatted_date,
            'serial_number' => $new_serial_number,
            'item_type_id' => $validated_data['item_type_id'],
            'batch_id' => '',
            'metrc_id' => '',
            'tare_weight' => $validated_data['tare_weight'],
            'gross_weight' => $validated_data['gross_weight'],
            'strain_id' => $validated_data['strain_id'],
            'product_id' => $validated_data['product_id'],
            'color_id' => 1,
            'clarity_id' => 1,
            'appearance_id' => 1,
        ];

        if ($validated_data['batch_id'] !== null) $item_data['batch_id'] = $validated_data['batch_id'];
        if ($validated_data['metrc_id'] !== null) $item_data['metrc_id'] = $validated_data['metrc_id'];
        if ($validated_data['color_id'] !== null) $item_data['color_id'] = $validated_data['color_id'];
        if ($validated_data['clarity_id'] !== null) $item_data['clarity_id'] = $validated_data['clarity_id'];
        if ($validated_data['appearance_id'] !== null) $item_data['appearance_id'] = $validated_data['appearance_id'];

        $item = Item::create($item_data);

        ItemStatus::create([
            'date_time' => $formatted_date,
            'operator_id' => $validated_data['operator_id'],
            'item_id' => $item->id,
            'status_id' => Status::where('status', 'IN')->first()->id,
            'note' => $request->note,
        ]);

        ItemWeight::create([
            'date_time' => $formatted_date,
            'operator_id' => $validated_data['operator_id'],
            'item_id' => $item->id,
            'gross_weight' => $validated_data['gross_weight'],
            'net_weight' => $validated_data['gross_weight'] - ($validated_data['tare_weight'] / $item->itemType->weightUnit->convert_to_grams),
            'note' => $request->note,
        ]);

        // return redirect()->route('items.index')->with('success', 'Item has been created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = Item::with([
            'operator',
            'itemType',
            'strain',
            'product',
            'color',
            'clarity',
            'appearance',
            'weights.operator',
            'statuses.operator',
            'statuses.status',
        ])->findOrFail($id);

        $current_weight = $item->weights()->orderBy('date_time', 'desc')->first();
        $current_status = $item->statuses()->orderBy('date_time', 'desc')->with(['status'])->first();
        $weight_unit = ItemType::findOrFail($item->item_type_id)->weightUnit()->first();

        return Inertia::render('Items/Show', [
            'item' => $item,
            'currentWeight' => $current_weight,
            'currentStatus' => $current_status,
            'weightUnit' => $weight_unit,
            'statuses' => StatusResource::collection(Status::where('enabled', true)->get()),
            'operators' => OperatorResource::collection(Operator::where('enabled', true)->get()),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        $current_status = ItemStatus::where('item_id', $item->id)->latest()->first()->status;
        $current_weight = ItemWeight::where('item_id', $item->id)->latest()->first();

        return Inertia::render('Items/Edit', [
            'item' => $item,
            'currentStatus' => $current_status,
            'currentWeight' => $current_weight,
            'statuses' => StatusResource::collection(Status::where('enabled', true)->orderBy('status')->get()),
            'operators' => OperatorResource::collection(Operator::where('enabled', true)->orderBy('operator')->get()),
            'itemTypes' => ItemTypeResource::collection(ItemType::where('enabled', true)->orderBy('item_type')->get()),
            'strains' => StrainResource::collection(Strain::where('enabled', true)->orderBy('strain')->get()),
            'products' => ProductResource::collection(Product::where('enabled', true)->orderBy('product')->get()),
            'colors' => ColorResource::collection(Color::where('enabled', true)->orderBy('color')->get()),
            'clarities' => ClarityResource::collection(Clarity::where('enabled', true)->orderBy('clarity')->get()),
            'appearances' => AppearanceResource::collection(Appearance::where('enabled', true)->orderBy('appearance')->get()),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ItemStoreRequest $request, string $id)
    {
        $validated_data = $request->validated();
        $formatted_date = Carbon::parse($validated_data['date_time'])->format('Y-m-d H:i:s');

        $item = Item::findOrFail($id);

        $item_data = [
            'operator_id' => $validated_data['operator_id'],
            'date_time' => $formatted_date,
            'item_type_id' => $validated_data['item_type_id'],
            'batch_id' => '',
            'metrc_id' => '',
            'tare_weight' => $validated_data['tare_weight'],
            'gross_weight' => $validated_data['gross_weight'],
            'strain_id' => $validated_data['strain_id'],
            'product_id' => $validated_data['product_id'],
            'color_id' => 1,
            'clarity_id' => 1,
            'appearance_id' => 1,
        ];

        if ($validated_data['batch_id'] !== null) $item_data['batch_id'] = $validated_data['batch_id'];
        if ($validated_data['metrc_id'] !== null) $item_data['metrc_id'] = $validated_data['metrc_id'];
        if ($validated_data['color_id'] !== null) $item_data['color_id'] = $validated_data['color_id'];
        if ($validated_data['clarity_id'] !== null) $item_data['clarity_id'] = $validated_data['clarity_id'];
        if ($validated_data['appearance_id'] !== null) $item_data['appearance_id'] = $validated_data['appearance_id'];

        $item->update($item_data);

        // ItemStatus::create([
        //     'date_time' => $formatted_date,
        //     'operator_id' => $validated_data['operator_id'],
        //     'item_id' => $id,
        //     'status_id' => $validated_data['status_id'],
        //     'note' => $request->note,
        // ]);

        // ItemWeight::create([
        //     'date_time' => $formatted_date,
        //     'operator_id' => $validated_data['operator_id'],
        //     'item_id' => $id,
        //     'gross_weight' => $validated_data['gross_weight'],
        //     'note' => $request->note,
        // ]);

        return redirect('/items')->with('success', 'Item has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $item->delete();

        return back()->with('delete', 'Item has been deleted!');
    }

    public function changeStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:items,id',
            'operator_id' => 'required|exists:operators,id',
            'status_id' => 'required|exists:statuses,id',
            'date_time' => 'required|date',
            'note' => 'nullable|string|max:255',
        ]);

        $item = Item::findOrFail($request->id);
        $formatted_date = Carbon::parse($request->date_time)->format('Y-m-d H:i:s');

        $item->update([
            'date_time' => $formatted_date,
            'operator_id' => $request->operator_id,
        ]);

        ItemStatus::create([
            'date_time' => $formatted_date,
            'operator_id' => $request->operator_id,
            'item_id' => $item->id,
            'status_id' => $request->status_id,
            'note' => $request->note,
        ]);

        return back()->with('success', 'Status has been changed!');
    }

    public function changeWeight(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:items,id',
            'operator_id' => 'required|exists:operators,id',
            'gross_weight' => 'required|numeric|min:0',
            'date_time' => 'required|date',
            'note' => 'nullable|string|max:255',
        ]);

        $item = Item::findOrFail($request->id);
        $formatted_date = Carbon::parse($request->date_time)->format('Y-m-d H:i:s');

        $item->update([
            'date_time' => $formatted_date,
            'operator_id' => $request->operator_id,
            'gross_weight' => $request->gross_weight,
        ]);

        ItemWeight::create([
            'date_time' => $formatted_date,
            'operator_id' => $request->operator_id,
            'item_id' => $item->id,
            'gross_weight' => $request->gross_weight,
            'net_weight' => $request->gross_weight - ($item->tare_weight / $item->itemType->weightUnit->convert_to_grams),
            'note' => $request->note,
        ]);

        return back()->with('success', 'Weight has been changed!');
    }

    public function batchChangeStatus(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:items,id',
            'operator_id' => 'required|exists:operators,id',
            'status_id' => 'required|exists:statuses,id',
            'date_time' => 'required|date',
            'note' => 'nullable|string|max:255',
        ]);

        foreach ($request->ids as $id) {
            $item = Item::findOrFail($id);
            $formatted_date = Carbon::parse($request->date_time)->format('Y-m-d H:i:s');

            $item->update([
                'operator_id' => $request->operator_id,
                'date_time' => $formatted_date,
            ]);

            ItemStatus::create([
                'date_time' => $formatted_date,
                'operator_id' => $request->operator_id,
                'item_id' => $item->id,
                'status_id' => $request->status_id,
                'note' => $request->note,
            ]);
        }

        return redirect('/items')->with('success', 'Item has been updated!');
    }

    public function batchChangeWeight(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:items,id',
            'operator_id' => 'required|exists:operators,id',
            'gross_weight' => 'required|numeric|min:0',
            'date_time' => 'required|date',
            'note' => 'nullable|string|max:255',
        ]);

        foreach ($request->ids as $id) {
            $item = Item::findOrFail($id);
            $formatted_date = Carbon::parse($request->date_time)->format('Y-m-d H:i:s');

            $item->update([
                'operator_id' => $request->operator_id,
                'date_time' => $formatted_date,
                'gross_weight' => $request->gross_weight,
            ]);

            ItemWeight::create([
                'date_time' => $formatted_date,
                'operator_id' => $request->operator_id,
                'item_id' => $item->id,
                'gross_weight' => $request->gross_weight,
                'net_weight' => $request->gross_weight - ($item->tare_weight / $item->itemType->weightUnit->convert_to_grams),
                'note' => $request->note,
            ]);
        }

        return redirect('/items')->with('success', 'Item has been updated!');
    }
}
