<?php

namespace App\Http\Controllers;

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

use App\Models\Operator;
use App\Models\ItemType;
use App\Models\WeightUnit;
use App\Models\Strain;
use App\Models\Product;
use App\Models\Color;
use App\Models\Clarity;
use App\Models\Appearance;
use App\Models\Status;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render("Attributes/Index", [
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        switch ($request->attribute) {
            case 'Operator':
                Operator::findOrFail($id)->delete();
                break;
            case 'Item Type':
                ItemType::findOrFail($id)->delete();
                break;
            case 'Strain':
                Strain::findOrFail($id)->delete();
                break;
            case 'Product':
                Product::findOrFail($id)->delete();
                break;
            case 'Color':
                Color::findOrFail($id)->delete();
                break;
            case 'Clarity':
                Clarity::findOrFail($id)->delete();
                break;
            case 'Appearance':
                Appearance::findOrFail($id)->delete();
                break;
            case 'Status':
                Status::findOrFail($id)->delete();
                break;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function delete(Request $request)
    {
        $attribute = $request->input('attribute');
        $id = $attribute['value'];
        $table = $request->input('table');

        switch ($table) {
            case 'Operator':
                Operator::findOrFail($id)->delete();
                break;
            case 'Item Type':
                ItemType::findOrFail($id)->delete();
                break;
            case 'Strain':
                Strain::findOrFail($id)->delete();
                break;
            case 'Product':
                Product::findOrFail($id)->delete();
                break;
            case 'Color':
                Color::findOrFail($id)->delete();
                break;
            case 'Clarity':
                Clarity::findOrFail($id)->delete();
                break;
            case 'Appearance':
                Appearance::findOrFail($id)->delete();
                break;
            case 'Status':
                Status::findOrFail($id)->delete();
                break;
        }

        return back()->with('delete', 'Item has been deleted!');
    }
}
