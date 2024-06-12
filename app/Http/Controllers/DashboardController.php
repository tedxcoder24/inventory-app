<?php

namespace App\Http\Controllers;

use App\Http\Resources\ItemResource;
use App\Models\ChangeItemStatus;
use App\Models\Item;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $from_date_time = $request->input('from_date_time', []);
        $to_date_time = $request->input("to_date_time", []);

        $from_date = isset($from_date_time) ? Carbon::parse($from_date_time) : Carbon::now()->startOfWeek();
        $to_date = isset($to_date_time) ? Carbon::parse($to_date_time) : Carbon::now()->endOfWeek();

        $current_week = [
            "start"=> Carbon::now()->startOfWeek(),
            "end" => Carbon::now()->endOfWeek(),
        ];

        $last_week = [
            "start" => Carbon::now()->subWeek()->startOfWeek(),
            "end" => Carbon::now()->subWeek()->endOfWeek(),
        ];

        $current_month = [
            "start" => Carbon::now()->startOfMonth(),
            "end"=> Carbon::now()->endOfMonth(),
        ];

        $last_month = [
            "start" => Carbon::now()->subMonth()->startOfMonth(),
            "end" => Carbon::now()->subMonth()->endOfMonth(),
        ];

        $inventories = ChangeItemStatus::whereBetween('date_time', [$from_date, $to_date])
            ->with(['status', 'changeable.product'])
            ->get()
            ->groupBy('changeable.product.product')
            ->map(function ($items, $productId) {
                $product = $items->first()->changeable->product;
                $statuses = $items->groupBy('status_id')
                    ->map(function ($statusItems, $statusId) {
                        $status = $statusItems->first()->status;
                        $totalWeight = $statusItems->reduce(function ($carry, $item) {
                            $weight = $item->changeable->gross_weight;
                            // $weightUnit = $item->changeable->weightUnit->weight_unit;
                            // if ($weightUnit === 'Grams') {
                            //     $carry['grams'] += $weight;
                            // } elseif ($weightUnit === 'Lbs') {
                            //     $carry['lbs'] += $weight;
                            // } elseif ($weightUnit === 'Jars') {
                            //     $carry['jars'] += $weight;
                            // }
                            $carry['grams'] += $weight;
                            $carry['lbs'] += $weight;
                            $carry['jars'] += $weight;
                            return $carry;
                        }, ['grams' => 0, 'lbs' => 0, 'jars' => 0]);
                        return [
                            'status' => $status,
                            'totalWeight' => $totalWeight
                        ];
                    });
                return [
                    'product' => $product,
                    'statuses' => $statuses
                ];
            });

        $products = Product::with([
            'items.statuses.status'
        ])->get();

        $last_items = Item::whereBetween('date_time', [$last_week['start'], $last_week['end']])->with(['statuses'])->get();

        return Inertia::render("Dashboard", [
            "items" => ItemResource::collection(Item::all()),
            "products" => $products,
            "currentItems" => $last_items,
            "inventories" => $inventories,
        ]);
    }
}
