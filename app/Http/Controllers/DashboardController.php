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
                    $status_data[strtolower($item_type)] = 0;
                }

                $status_changes = ItemStatus::where('status_id', $status->id)
                    ->whereBetween('date_time', [$from_date, $to_date])
                    ->whereHas('item', function ($query) use ($product) {
                        $query->where('product_id', $product->id);
                    })
                    ->get();

                foreach ($status_changes as $status_change) {
                    $weight_change = ItemWeight::where('item_id', $status_change->item_id)
                        ->where('date_time', $status_change->date_time)
                        ->first();

                    $weight = $weight_change ? $weight_change->gross_weight : 0;
                    $status_data['weight'] += $weight;
                    $status_data['count']++;

                    $item = $status_change->item;
                    $item_type = $item->itemType->item_type;
                    $status_data[strtolower($item_type)]++;
                }

                foreach ($item_types as $item_type) {
                    $count_field = strtolower($item_type);
                    if ($status_data[$count_field] > 0) {
                        $data[] = [
                            'product' => $product->product,
                            'statuses' => [
                                $status->status => [
                                    'weight' => $status_data['weight'],
                                    'count' => $status_data['count'],
                                    $count_field => $status_data[$count_field],
                                ],
                            ],
                        ];
                    }
                }

                // // Only add status data if there is relevant data
                // if ($status_data['count'] > 0) {
                //     $product_data['statuses'][$status->status] = $status_data;
                // }
            }

            // if (!empty($product_data['statuses'])) {
            //     $data[] = $product_data;
            // }
        }


        return Inertia::render("Dashboard", [
            'data' => $data,
            'from_date' => $from_date,
            'to_date' => $to_date
        ]);
    }
}
