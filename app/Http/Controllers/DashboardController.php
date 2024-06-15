<?php

namespace App\Http\Controllers;

use App\Http\Resources\ItemResource;
use App\Models\Item;
use App\Models\ItemChange;
use App\Models\ItemStatus;
use App\Models\ItemType;
use App\Models\ItemWeight;
use App\Models\Product;
use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    // public function index(Request $request)
    // {
    //     $from_date_time = $request->input('from_date_time');
    //     $to_date_time = $request->input("to_date_time");

    //     $from_date = isset($from_date_time) ? Carbon::parse($from_date_time) : Carbon::now()->startOfWeek();
    //     $to_date = isset($to_date_time) ? Carbon::parse($to_date_time) : Carbon::now()->endOfWeek();

    //     $current_week = [
    //         "start"=> Carbon::now()->startOfWeek(),
    //         "end" => Carbon::now()->endOfWeek(),
    //     ];

    //     $last_week = [
    //         "start" => Carbon::now()->subWeek()->startOfWeek(),
    //         "end" => Carbon::now()->subWeek()->endOfWeek(),
    //     ];

    //     $current_month = [
    //         "start" => Carbon::now()->startOfMonth(),
    //         "end"=> Carbon::now()->endOfMonth(),
    //     ];

    //     $last_month = [
    //         "start" => Carbon::now()->subMonth()->startOfMonth(),
    //         "end" => Carbon::now()->subMonth()->endOfMonth(),
    //     ];

    //     $inventories = ItemChange::whereBetween("date_time", [$from_date, $to_date])
    //         ->with(['status', 'item.product'])
    //         ->get()
    //         ->groupBy('item.product.product')
    //         ->map(function ($items, $productId) {
    //             $statuses = $items->groupBy('status_id')
    //                 ->map(function ($statusItems, $statusId) {
    //                     $status = $statusItems->first()->status;
    //                     $totalWeight = $statusItems->reduce(function ($carry, $item) {
    //                         $weight = $item->gross_weight;
    //                         $weightUnit = $item->item->itemType->weightUnit->weight_unit;
    //                         if ($weightUnit === 'Grams') {
    //                             $carry['g'] += $weight;
    //                             $carry['lbs'] += $weight * 0.00220462;
    //                             $carry['kg'] += $weight * 0.001;
    //                             $carry['oz'] += $weight * 0.035274;
    //                         } elseif ($weightUnit === 'Kilograms') {
    //                             $carry['kg'] += $weight;
    //                             $carry['g'] += $weight * 1000;
    //                             $carry['lbs'] += $weight * 2.20462;
    //                             $carry['oz'] += $weight * 35.274;
    //                         } elseif ($weightUnit === 'Ounce') {
    //                             $carry['oz'] += $weight;
    //                             $carry['g'] += $weight * 28.3495;
    //                             $carry['kg'] += $weight * 0.0283495;
    //                             $carry['lbs'] += $weight * 0.0625;
    //                         } elseif ($weightUnit === 'Pounds') {
    //                             $carry['lbs'] += $weight;
    //                             $carry['g'] += $weight * 453.592;
    //                             $carry['kg'] += $weight * 0.453592;
    //                             $carry['lbs'] += $weight * 2.20462;
    //                         }
    //                         return $carry;
    //                     }, ['g' => 0, 'kg' => 0, 'lbs' => 0, 'oz' => 0]);
    //                     return [
    //                         'status' => $status,
    //                         'totalWeight' => $totalWeight
    //                     ];
    //                 });
    //             return [
    //                 'statuses' => $statuses
    //             ];
    //         });

    //     $products = Product::with([
    //         'items.changeHistories'
    //     ])->get();

    //     $last_items = Item::whereBetween('date_time', [$last_week['start'], $last_week['end']])->with(['statuses'])->get();

    //     return Inertia::render("Dashboard", [
    //         "items" => ItemResource::collection(Item::all()),
    //         "products" => $products,
    //         "currentItems" => $last_items,
    //         "inventories" => $inventories,
    //     ]);
    // }

    public function index(Request $request)
    {
        $from_date_time = $request->input('from_date_time');
        $to_date_time = $request->input("to_date_time");

        $from_date = isset($from_date_time) ? Carbon::parse($from_date_time) : Carbon::now()->startOfWeek();
        $to_date = isset($to_date_time) ? Carbon::parse($to_date_time) : Carbon::now()->endOfWeek();

        $products = Product::all();
        $statuses = Status::all();
        $item_types = ItemType::all()->pluck('item_type')->toArray();
        $data = [];

        foreach ($products as $product) {
            $product_data = [
                'product' => $product->product,
                'statuses' => [],
            ];

            foreach ($statuses as $status) {
                $status_data = [
                    'weight' => 0,
                    'count' => 0,
                ];

                foreach ($item_types as $item_type) {
                    $status_data[strtolower($item_type) . 'Count'] = 0;
                }

                $status_changes = ItemStatus::where('status_id', $status->id)
                    ->whereBetween('date_time', [$from_date, $to_date])
                    ->whereHas('item', function ($query) use ($product) {
                        $query->where('product_id', $product->id);
                    })
                    ->get();
                
                foreach ($status_changes as $status_change) {
                    $weight_change = ItemWeight::where('item_id', $status_change->id)
                        ->where('date_time', $status_change->date_time)
                        ->first();
                    
                    $weight = $weight_change ? $weight_change->gross_weight : 0;
                    $status_data['weight'] += $weight;
                    $status_data['count']++;

                    $item = $status_change->item;
                    $item_type = $item->itemType->item_type;
                    $status_data[strtolower($item_type) . 'Count']++;
                }

                $product_data['statuses'][$status->status] = $status_data;
            }

            $data[] = $product_data;
        }


        return Inertia::render("Dashboard", [
            'data' => $data,
            'from_date' => $from_date,
            'to_date' => $to_date
        ]);
    }
}
