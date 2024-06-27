<?php

namespace App\Http\Controllers;

use App\Models\ItemType;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function getInventoryStats($from_date, $to_date)
    {
        $data = [];

        // Get all item types
        $itemTypes = ItemType::all();

        foreach ($itemTypes as $itemType) {
            $itemTypeData = [
                'itemType' => $itemType->item_type,
                'products' => []
            ];

            // Get all products associated with this item type
            $products = Product::whereHas('items', function ($query) use ($itemType, $from_date, $to_date) {
                $query->where('item_type_id', $itemType->id)
                    ->whereHas('statuses', function ($query) use ($from_date, $to_date) {
                        $query->whereBetween('date_time', [$from_date, $to_date]);
                    })
                    ->whereHas('weights', function ($query) use ($from_date, $to_date) {
                        $query->whereBetween('date_time', [$from_date, $to_date]);
                    });
            })->get();

            foreach ($products as $product) {
                $productData = [
                    'product' => $product->product,
                    'count' => 0,
                    'statuses' => []
                ];

                // Get items for the product and item type within the date range
                $items = $product->items()
                    ->where('item_type_id', $itemType->id)
                    ->get();

                $statusAggregation = [];

                foreach ($items as $item) {
                    // Fetch statuses and weights within the date range
                    $statuses = $item->statuses()
                        ->whereBetween('date_time', [$from_date, $to_date])
                        ->get();
                    $weights = $item->weights()
                        ->whereBetween('date_time', [$from_date, $to_date])
                        ->get();

                    foreach ($statuses as $status) {
                        $statusDateTime = Carbon::parse($status->date_time);
                        foreach ($weights as $weight) {
                            $weightDateTime = Carbon::parse($weight->date_time);

                            // Ensure the status and weight have matching date times (or close to each other)
                            if ($statusDateTime->isSameDay($weightDateTime)) {
                                $statusName = $status->status->status;
                                $grossWeight = $weight->gross_weight;

                                // Aggregate status data
                                if (!isset($statusAggregation[$statusName])) {
                                    $statusAggregation[$statusName] = [
                                        'status' => $statusName,
                                        'weight' => 0,
                                        'count' => 0
                                    ];
                                }
                                $statusAggregation[$statusName]['weight'] += $grossWeight;
                                $statusAggregation[$statusName]['count']++;
                            }
                        }
                    }
                }

                // Increment count of items
                $productData['count'] = $items->count();

                // Convert aggregated status data to array format if not empty
                if (!empty($statusAggregation)) {
                    foreach ($statusAggregation as $statusData) {
                        $productData['statuses'][] = [
                            'status' => $statusData['status'],
                            'weight' => $statusData['weight'],
                        ];
                    }
                    $itemTypeData['products'][] = $productData;
                }
            }

            // Add item type data only if it has products with statuses
            if (!empty($itemTypeData['products'])) {
                $data[] = $itemTypeData;
            }
        }

        foreach ($data as &$item) {
            foreach ($item['products'] as &$product) {
                usort($product['statuses'], function ($a, $b) {
                    return strcmp($a['status'], $b['status']);
                });
            }
        }

        return $data;
    }

    public function getCurrentStats()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfWeek = Carbon::now()->endOfWeek();

        $data = [];

        // Get all item types
        $itemTypes = ItemType::all();

        foreach ($itemTypes as $itemType) {
            $itemTypeData = [
                'itemType' => $itemType->item_type,
                'products' => []
            ];

            // Get all products associated with this item type
            $products = Product::whereHas('items', function ($query) use ($itemType) {
                $query->where('item_type_id', $itemType->id);
            })->get();

            foreach ($products as $product) {
                $productData = [
                    'product' => $product->product,
                    'count' => 0,
                    'statuses' => []
                ];

                // Get items for the product and item type within the current week
                $items = $product->items()
                    ->where('item_type_id', $itemType->id)
                    ->whereHas('statuses', function ($query) use ($startOfMonth, $endOfWeek) {
                        $query->whereBetween('date_time', [$startOfMonth, $endOfWeek]);
                    })
                    ->whereHas('weights', function ($query) use ($startOfMonth, $endOfWeek) {
                        $query->whereBetween('date_time', [$startOfMonth, $endOfWeek]);
                    })
                    ->get();

                $statusAggregation = [];

                foreach ($items as $item) {
                    $latestStatus = $item->statuses()
                        ->whereBetween('date_time', [$startOfMonth, $endOfWeek])
                        ->latest()
                        ->first();

                    $latestWeight = $item->weights()
                        ->whereBetween('date_time', [$startOfMonth, $endOfWeek])
                        ->latest()
                        ->first();

                    if ($latestStatus && $latestWeight) {
                        $statusName = $latestStatus->status->status;
                        $grossWeight = $latestWeight->gross_weight;

                        // Aggregate status data
                        if (!isset($statusAggregation[$statusName])) {
                            $statusAggregation[$statusName] = [
                                'status' => $statusName,
                                'weight' => $grossWeight
                            ];
                        }
                    }
                }

                // Increment count of items
                $productData['count'] = $items->count();

                // Convert aggregated status data to array format if not empty
                if (!empty($statusAggregation)) {
                    foreach ($statusAggregation as $statusData) {
                        $productData['statuses'][] = [
                            'status' => $statusData['status'],
                            'weight' => $statusData['weight']
                        ];
                    }
                    $itemTypeData['products'][] = $productData;
                }
            }

            // Add item type data only if it has products with statuses
            if (!empty($itemTypeData['products'])) {
                $data[] = $itemTypeData;
            }
        }

        foreach ($data as &$item) {
            foreach ($item['products'] as &$product) {
                usort($product['statuses'], function ($a, $b) {
                    return strcmp($a['status'], $b['status']);
                });
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

        $current_stats_data = $this->getCurrentStats();
        $current_week_data = $this->getInventoryStats($current_week_start, $current_week_end);
        $previous_week_data = $this->getInventoryStats($previous_week_start, $previous_week_end);
        $current_month_data = $this->getInventoryStats($current_month_start, $current_month_end);
        $previous_month_data = $this->getInventoryStats($previous_month_start, $previous_month_end);

        return Inertia::render("Dashboard/Index", [
            'current_data' => $current_stats_data,
            'current_week_data' => $current_week_data,
            'previous_week_data' => $previous_week_data,
            'current_month_data' => $current_month_data,
            'previous_month_data' => $previous_month_data,
            'from_date' => $from_date,
            'to_date' => $to_date
        ]);
    }
}
