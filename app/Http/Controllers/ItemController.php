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
use App\Models\ChangeItemWeight;
use App\Models\ChangeItemStatus;
use App\Models\Status;
use App\Models\Config;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Items/Index', [
            'items' => ItemResource::collection(Item::all()),
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
            'operators' => OperatorResource::collection(Operator::all()),
            'itemTypes' => ItemTypeResource::collection(ItemType::all()),
            'weightUnits' => WeightUnitResource::collection(WeightUnit::all()),
            'strains' => StrainResource::collection(Strain::all()),
            'products' => ProductResource::collection(Product::all()),
            'colors' => ColorResource::collection(Color::all()),
            'clarities' => ClarityResource::collection(Clarity::all()),
            'appearances' => AppearanceResource::collection(Appearance::all()),
            'statuses' => StatusResource::collection(Status::all()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ItemStoreRequest $request)
    {
        $validatedData = $request->validated();

        $config = Config::first();
        $serial_number = $config->last_serial_number + 1;
        $config->update(['last_serial_number' => $serial_number]);
        
        $formattedDate = Carbon::parse($validatedData['date_time'])->format('Y-m-d H:i:s');
        $item = Item::create([
            'operator_id' => $validatedData['operator_id'],
            'date_time' => $formattedDate,
            'serial_number' => $serial_number,
            'item_type_id' => $validatedData['item_type_id'],
            'batch_id' => $validatedData['batch_id'],
            'metrc_id' => $validatedData['metrc_id'],
            'tare_weight' => $validatedData['tare_weight'],
            'gross_weight' => $validatedData['gross_weight'],
            'weight_unit_id' => $validatedData['weight_unit_id'],
            'strain_id' => $validatedData['strain_id'],
            'product_id' => $validatedData['product_id'],
            'color_id' => $validatedData['color_id'],
            'clarity_id' => $validatedData['clarity_id'],
            'appearance_id' => $validatedData['appearance_id'],
        ]);

        $item->weights()->create([
            'date_time' => $formattedDate,
            'operator_id'=> $validatedData['operator_id'],
            'gross_weight' => $validatedData['gross_weight'],
            'note' => $request->note,
        ]);

        $item->statuses()->create([
            'date_time' => $formattedDate,
            'operator_id'=> $validatedData['operator_id'],
            'status_id' => Status::where('status', 'IN')->first()->id,
            'note' => $request->note,
        ]);

        return redirect()->route('items.index')->with('success', 'Item has been created!');
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
            'statuses.status', 
            'statuses.operator'
        ])->findOrFail($id);

        return Inertia::render('Items/Show', [
            'item' => $item,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        return Inertia::render('Items/Edit', [
            'item' => $item,
            'operators' => OperatorResource::collection(Operator::all()),
            'itemTypes' => ItemTypeResource::collection(ItemType::all()),
            'weightUnits' => WeightUnitResource::collection(WeightUnit::all()),
            'strains' => StrainResource::collection(Strain::all()),
            'products' => ProductResource::collection(Product::all()),
            'colors' => ColorResource::collection(Color::all()),
            'clarities' => ClarityResource::collection(Clarity::all()),
            'appearances' => AppearanceResource::collection(Appearance::all()),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ItemStoreRequest $request, string $id)
    {
        $validatedData = $request->validated();

        Item::findOrFail($id)->update($validatedData);

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
            $formattedDate = Carbon::parse($request->date_time)->format('Y-m-d H:i:s');

            $item->update([
                'operator_id' => $request->operator_id,
                'date_time' => $formattedDate,
            ]);

            $item->statuses()->create([
                'date_time' => $formattedDate,
                'operator_id'=> $request->operator_id,
                'status_id' => $request->status_id,
                'note' => $request->note,
            ]);
        }

        return redirect('/items')->with('success', 'Item has been updated!');
    }
}
