<?php

namespace App\Repositories;

use App\Models\Inventory;
use App\Models\ProductVariant;
use App\Models\SaleOrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReportRepository extends BaseRepository
{

    public function getSuggestOrder($weeks, $reserveDays, $search = '')
    {
        $startDate = now()->subWeeks($weeks)->startOfDay();
        $endDate = now()->endOfDay();

        $variants = ProductVariant::with(['product', 'attributes.attribute', 'unit'])
            ->when($search, function ($q) use ($search) {
                $q->whereHas('product', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%$search%")
                        ->orWhere('code', 'like', "%$search%");
                });
            })
            ->get();

        $result = [];

        foreach ($variants as $variant) {
            $sold = SaleOrderItem::where('product_variant_id', $variant->id)
                ->whereHas('salesOrder', function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('order_date', [$startDate, $endDate])
                        ->where('status', 'completed');
                })
                ->sum('quantity_ordered');

            $days = $weeks * 7;
            $avgSoldPerDay = $days > 0 ? round($sold / $days, 2) : 0;

            $inventory = Inventory::where('product_variant_id', $variant->id)->first();
            $currentStock = $inventory ? $inventory->quantity_on_hand : 0;

            $minStock = $variant->product->min_stock ?? 0;

            $suggestQty = max(0, ($avgSoldPerDay * $reserveDays) - $currentStock + $minStock);

            // Lấy tên biến thể: Tên sản phẩm + các giá trị thuộc tính
            $variantName = $variant->product->name;
            if ($variant->attributes && $variant->attributes->count() > 0) {
                $attrNames = $variant->attributes->map(function ($attrVal) {
                    return $attrVal->name;
                })->implode(' - ');
                $variantName .= ' - ' . $attrNames;
            }

            $result[] = [
                'variant_id' => $variant->id,
                'product_code' => $variant->product->code,
                'variant_name' => $variantName,
                'current_stock' => $currentStock,
                'avg_sold_per_day' => $avgSoldPerDay,
                'reserve_days' => $reserveDays,
                'suggest_qty' => round($suggestQty),
                'unit' => $variant->unit ? $variant->unit->name : ($variant->product->defaultUnit->name ?? ''),
            ];
        }

        usort($result, function ($a, $b) {
            return $b['suggest_qty'] <=> $a['suggest_qty'];
        });

        return $result;
    }
}
