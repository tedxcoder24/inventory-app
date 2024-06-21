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
    public function getInventoryStats($from_date, $to_date)
    {
        $item_types = ItemType::all();
        $statuses = Status::all();
        $data = [];

        foreach ($item_types as $itemType) {
            $itemTypeData = [
                'itemType' => $itemType->item_type,
                'products' => []
            ];
        
            $products = Product::whereHas('items', function ($query) use ($itemType) {
                $query->where('item_type_id', $itemType->id);
            })->get();
        
            foreach ($products as $product) {
                $productData = [
                    'product' => $product->product,
                    'statuses' => []
                ];
        
                foreach ($statuses as $status) {
                    $items = Item::where('item_type_id', $itemType->id)
                        ->where('product_id', $product->id)
                        ->whereHas('statuses', function ($query) use ($status, $from_date, $to_date) {
                            $query->where('status_id', $status->id)
                                  ->whereBetween('date_time', [$from_date, $to_date]);
                        })
                        ->get();
        
                    $weight = 0;
                    $count = 0;
        
                    foreach ($items as $item) {
                        $itemWeights = $item->weights()->whereBetween('date_time', [$from_date, $to_date])->get();
                        foreach ($itemWeights as $itemWeight) {
                            $weight += $itemWeight->gross_weight;
                        }
                        $count++;
                    }
        
                    if ($count > 0) {
                        $productData['statuses'][] = [
                            'status' => $status->status,
                            'weight' => $weight,
                            'count' => $count
                        ];
                    }
                }
        
                if (!empty($productData['statuses'])) {
                    $itemTypeData['products'][] = $productData;
                }
            }
        
            if (!empty($itemTypeData['products'])) {
                $data[] = $itemTypeData;
            }
        }

        return $data;
    }

    public function index(Request $request)
    {
        $from_date_time = $request->input('from_date_time');
        $to_date_time = $request->input("to_date_time");

        $from_date = isset($from_date_time) ? Carbon::parse($from_date_time) : Carbon::now()->startOfWeek();
        $to_date = isset($to_date_time) ? Carbon::parse($to_date_time) : Carbon::now()->endOfWeek();

        // Current week
        $current_week_start = Carbon::now()->startOfWeek();
        $current_week_end = Carbon::now()->endOfWeek();

        // Previous week
        $previous_week_start = Carbon::now()->subWeek()->startOfWeek();
        $previous_week_end = Carbon::now()->subWeek()->endOfWeek();

        // Current month
        $current_month_start = Carbon::now()->startOfMonth();
        $current_month_end = Carbon::now()->endOfMonth();

        // Previous month
        $previous_month_start = Carbon::now()->subMonth()->startOfMonth();
        $previous_month_end = Carbon::now()->subMonth()->endOfMonth();

        $current_stats_data = $this->getInventoryStats($from_date, $to_date);
        $current_week_data = $this->getInventoryStats($current_week_start, $current_week_end);
        $previous_week_data = $this->getInventoryStats($previous_week_start, $previous_week_end);
        $current_month_data = $this->getInventoryStats($current_month_start, $current_month_end);
        $previous_month_data = $this->getInventoryStats($previous_month_start, $previous_month_end);

        return Inertia::render("Dashboard/Index", [
            'current_data' => $current_stats_data,
            'current_week_data' => $current_week_data,
            'previous_week_data'=> $previous_week_data,
            'current_month_data'=> $current_month_data,
            'previous_month_data'=> $previous_month_data,
            'from_date' => $from_date,
            'to_date' => $to_date
        ]);
    }
}
