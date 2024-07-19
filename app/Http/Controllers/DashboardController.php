<?php

namespace App\Http\Controllers;

use App\Models\ItemType;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function getCurrentInventory()
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
            $products = Product::whereHas('items', function ($query) use ($itemType) {
                $query->where('item_type_id', $itemType->id);
            })->get();

            foreach ($products as $product) {
                $productData = [
                    'product' => $product->product,
                    'count' => 0,
                    'statuses' => []
                ];

                $items = $product->items()
                    ->where('item_type_id', $itemType->id)
                    ->whereHas('statuses', function ($query) {
                        $query->where('id', function ($subQuery) {
                            $subQuery->select(DB::raw('MAX(id)'))
                                ->from('item_statuses as is')
                                ->whereColumn('is.item_id', 'items.id')
                                ->groupBy('is.item_id');
                        })
                            ->whereHas('status', function ($query) {
                                $query->where('status', 'IN');
                            });
                    })
                    ->get();

                $statusAggregation = [];

                foreach ($items as $item) {
                    $latestStatus = $item->statuses()
                        ->orderByDesc('date_time')
                        ->first();

                    $latestWeight = $item->weights()
                        ->orderByDesc('date_time')
                        ->first();

                    if ($latestStatus && $latestWeight && $latestStatus->status->status === 'IN') {
                        $statusName = $latestStatus->status->status;
                        $netWeight = $latestWeight->net_weight * $item->itemType->weightUnit->convert_to_grams;

                        // Aggregate status data
                        if (!isset($statusAggregation[$statusName])) {
                            $statusAggregation[$statusName] = [
                                'status' => $statusName,
                                'weight' => 0,
                                'count' => 0
                            ];
                        }
                        $statusAggregation[$statusName]['weight'] += $netWeight;
                        $statusAggregation[$statusName]['count']++;
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
                            'count' => $statusData['count'],
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

        return $data;
    }

    public function getHistoricalStats()
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
            $products = Product::whereHas('items', function ($query) use ($itemType) {
                $query->where('item_type_id', $itemType->id);
            })->get();

            foreach ($products as $product) {
                $productData = [
                    'product' => $product->product,
                    'count' => 0,
                    'statuses' => []
                ];

                // Get items for the product and item type where the last status is not "IN"
                $items = $product->items()
                    ->where('item_type_id', $itemType->id)
                    ->whereHas('statuses', function ($query) {
                        $query->whereIn('id', function ($subQuery) {
                            $subQuery->select(DB::raw('MAX(id)'))
                                ->from('item_statuses')
                                ->groupBy('item_id')
                                ->havingRaw('MAX(status_id) != (SELECT id FROM statuses WHERE status = "IN" LIMIT 1)');
                        });
                    })
                    ->get();

                $statusAggregation = [];

                foreach ($items as $item) {
                    $latestStatus = $item->statuses()
                        ->orderByDesc('date_time')
                        ->first();

                    $latestWeight = $item->weights()
                        ->orderByDesc('date_time')
                        ->first();

                    if ($latestStatus && $latestWeight && $latestStatus->status->status !== 'IN') {
                        $statusName = $latestStatus->status->status;
                        $netWeight = $latestWeight->net_weight * $item->itemType->weightUnit->convert_to_grams;

                        // Aggregate status data
                        if (!isset($statusAggregation[$statusName])) {
                            $statusAggregation[$statusName] = [
                                'status' => $statusName,
                                'weight' => 0,
                                'count' => 0
                            ];
                        }
                        $statusAggregation[$statusName]['weight'] += $netWeight;
                        $statusAggregation[$statusName]['count']++;
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
                            'count' => $statusData['count'],
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

        return $data;
    }

    public function getProductionStats($startDate, $endDate)
    {
        $data = [];

        // Convert date strings to Carbon instances
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

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

                // Get items for the product and item type where the first status is "IN" and within the date range
                $items = $product->items()
                    ->where('item_type_id', $itemType->id)
                    ->whereHas('statuses', function ($query) use ($startDate, $endDate) {
                        $query->where('status_id', function ($query) {
                            $query->select('id')
                                ->from('statuses')
                                ->where('status', 'IN')
                                ->orderBy('date_time')
                                ->limit(1);
                        })->whereBetween('date_time', [$startDate, $endDate]);
                    })
                    ->get();

                $statusAggregation = [];

                foreach ($items as $item) {
                    $firstStatusRecord = $item->statuses()
                        ->whereHas('status', function ($query) {
                            $query->where('status', 'IN');
                        })
                        ->orderBy('date_time')
                        ->first();

                    $firstWeight = $item->weights()
                        ->orderBy('date_time')
                        ->first();

                    if ($firstStatusRecord && $firstWeight && Carbon::parse($firstStatusRecord->date_time)->between($startDate, $endDate)) {
                        $statusName = $firstStatusRecord->status->status;
                        $netWeight = $firstWeight->net_weight;

                        // Aggregate status data
                        if (!isset($statusAggregation[$statusName])) {
                            $statusAggregation[$statusName] = [
                                'status' => $statusName,
                                'weight' => 0,
                                'count' => 0
                            ];
                        }
                        $statusAggregation[$statusName]['weight'] += $netWeight;
                        $statusAggregation[$statusName]['count']++;
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
                            'count' => $statusData['count'],
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

        return $data;
    }


    public function getTodayProductionStats()
    {
        $today = Carbon::today();
        $startOfToday = Carbon::now(config('app.timezone'))->startOfDay();
        $endOfToday = Carbon::now(config('app.timezone'))->endOfDay();
        return $this->getProductionStats($startOfToday, $endOfToday);
    }

    public function getYesterdayProductionStats()
    {
        $yesterday = Carbon::yesterday();
        $startOfYesterday = Carbon::yesterday(config('app.timezone'))->startOfDay();
        $endOfYesterday = Carbon::yesterday(config('app.timezone'))->endOfDay();
        return $this->getProductionStats($startOfYesterday, $endOfYesterday);
    }

    public function getCurrentWeekProductionStats()
    {
        $startOfWeek = Carbon::now(config('app.timezone'))->startOfWeek();
        $endOfWeek = Carbon::now(config('app.timezone'))->endOfWeek();
        return $this->getProductionStats($startOfWeek, $endOfWeek);
    }

    public function getPreviousWeekProductionStats()
    {
        $startOfPreviousWeek = Carbon::now(config('app.timezone'))->subWeek()->startOfWeek();
        $endOfPreviousWeek = Carbon::now(config('app.timezone'))->subWeek()->endOfWeek();
        return $this->getProductionStats($startOfPreviousWeek, $endOfPreviousWeek);
    }

    public function getCurrentMonthProductionStats()
    {
        $startOfMonth = Carbon::now(config('app.timezone'))->startOfMonth();
        $endOfMonth = Carbon::now(config('app.timezone'))->endOfMonth();
        return $this->getProductionStats($startOfMonth, $endOfMonth);
    }

    public function getPreviousMonthProductionStats()
    {
        $startOfPreviousMonth = Carbon::now(config('app.timezone'))->subMonth()->startOfMonth();
        $endOfPreviousMonth = Carbon::now(config('app.timezone'))->subMonth()->endOfMonth();
        return $this->getProductionStats($startOfPreviousMonth, $endOfPreviousMonth);
    }


    public function index(Request $request)
    {
        $current_inventory = $this->getCurrentInventory();
        $historical_stats = $this->getHistoricalStats();

        $today_data = $this->getTodayProductionStats();
        $yesterday_data = $this->getYesterdayProductionStats();
        $current_week_data = $this->getCurrentWeekProductionStats();
        $previous_week_data = $this->getPreviousWeekProductionStats();
        $current_month_data = $this->getCurrentMonthProductionStats();
        $previous_month_data = $this->getPreviousMonthProductionStats();

        return Inertia::render("Dashboard/Index", [
            'current_data' => $current_inventory,
            'historical_stats' => $historical_stats,
            'today_data' => $today_data,
            'yesterday_data' => $yesterday_data,
            'current_week_data' => $current_week_data,
            'previous_week_data' => $previous_week_data,
            'current_month_data' => $current_month_data,
            'previous_month_data' => $previous_month_data,
        ]);
    }

    public function getData()
    {
        $current_inventory = $this->getCurrentInventory();
        $historical_stats = $this->getHistoricalStats();

        $today_data = $this->getTodayProductionStats();
        $yesterday_data = $this->getYesterdayProductionStats();
        $current_week_data = $this->getCurrentWeekProductionStats();
        $previous_week_data = $this->getPreviousWeekProductionStats();
        $current_month_data = $this->getCurrentMonthProductionStats();
        $previous_month_data = $this->getPreviousMonthProductionStats();

        return response()->json([
            'current_data' => $current_inventory,
            'historical_stats' => $historical_stats,
            'today_data' => $today_data,
            'yesterday_data' => $yesterday_data,
            'current_week_data' => $current_week_data,
            'previous_week_data' => $previous_week_data,
            'current_month_data' => $current_month_data,
            'previous_month_data' => $previous_month_data,
        ]);
    }
}
