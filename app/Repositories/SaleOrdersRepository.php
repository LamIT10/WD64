<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\ProductUnitConversion;
use App\Models\ProductVariant;
use App\Models\SaleOrder;
use App\Models\SaleOrderItem;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class SaleOrdersRepository extends BaseRepository
{
    protected $product;
    protected $saleOrderItem;
    public function __construct(SaleOrder $saleOrder, Product $product, SaleOrderItem $saleOrderItem)
    {
        $this->handleModel = $saleOrder;
        $this->saleOrderItem = $saleOrderItem;
        $this->product = $product;
    }
    public function index(Request $request)
    {
        try {
            $query = $this->handleModel
                ->with([
                    'customer' => function ($query) {
                        $query->select('id', 'name', 'phone', 'email');
                    },
                    'items' => function ($query) {
                        $query->select(
                            'id',
                            'sales_order_id',
                            'product_variant_id',
                            'quantity_ordered',
                            'unit_id',
                            'unit_price',
                            'subtotal'
                        )
                            ->with([
                                'productVariant' => function ($query) {
                                    $query->select('id', 'product_id')
                                        ->with([
                                            'product' => function ($query) {
                                                $query->select('id', 'name');
                                            },
                                            'attributes' => function ($query) {
                                                $query->select('attribute_values.id', 'attribute_values.name');
                                            }
                                        ]);
                                },
                                'unit' => function ($query) {
                                    $query->select('id', 'name');
                                }
                            ]);
                    }
                ])
                ->select(
                    'id',
                    'customer_id',
                    'order_date',
                    'status',
                    'total_amount',
                    'address_delivery',
                    'created_at'
                );

            if ($request->has('status') && !empty($request->status)) {
                $query->where('status', $request->status);
            }
            $orders = $query->get()->map(function ($order) {
                $order->total_quantity = $order->items->sum('quantity_ordered');
                return $order;
            });

            return $orders;
        } catch (\Throwable $th) {
            Log::error('Lỗi khi lấy danh sách đơn xuất: ' . $th->getMessage());
            return ['error' => 'Lỗi khi lấy danh sách đơn xuất: ' . $th->getMessage()];
        }
    }
    public function searchProduct(Request $request)
    {
        $searchTerm = $request->input('searchProduct', '');
        $products = Product::query()
            ->where('name', 'LIKE', "%{$searchTerm}%")
            ->with([
                'productVariants' => function ($query) {
                    $query->select('id', 'product_id', 'sale_price')
                        ->with([
                            'attributes' => function ($query) {
                                $query->select('attribute_values.id', 'attribute_values.name');
                            }
                        ]);
                },
                'unitConversions' => function ($query) {
                    $query->select('id', 'product_id', 'from_unit_id', 'to_unit_id', 'conversion_factor')
                        ->with([
                            'toUnit' => function ($query) {
                                $query->select('id', 'name');
                            }
                        ]);
                }
            ])
            ->select('id', 'name', 'default_unit_id')
            ->get();
        $result = [];

        foreach ($products as $product) {
            $defaultUnit = Unit::find($product->default_unit_id);
            foreach ($product->productVariants as $variant) {
                $attributeNames = $variant->attributes->pluck('name')->implode('-');
                $attributeValueIds = $variant->attributes->pluck('id')->toArray();
                $productName = $product->name;
                if ($attributeNames) {
                    $productName .= '-' . $attributeNames;
                }

                $result[] = [
                    'variant_id' => $variant->id,
                    'product_id' => $product->id,
                    'product_name' => $productName,
                    'sale_price' => $variant->sale_price,
                    'attribute_value_id' => $attributeValueIds,
                    'default_unit_id' => $product->default_unit_id,
                    'default_unit_name' => $defaultUnit ? $defaultUnit->name : 'cái',
                ];
            }
        }

        return response()->json($result);
    }
    public function getAllVariantsByProductId($productId)
    {
        $product = Product::query()
            ->where('id', $productId)
            ->with([
                'productVariants' => function ($query) {
                    $query->select('id', 'product_id', 'sale_price')
                        ->with([
                            'attributes' => function ($query) {
                                $query->select('attribute_values.id', 'attribute_values.name');
                            }
                        ]);
                }
            ])
            ->select('id', 'name')
            ->firstOrFail();

        $result = [];

        foreach ($product->productVariants as $variant) {
            $attributeNames = $variant->attributes->pluck('name')->implode('-');
            $attributeValueIds = $variant->attributes->pluck('id')->toArray();

            $productName = $product->name;
            if ($attributeNames) {
                $productName .= '-' . $attributeNames;
            }

            $result[] = [
                'variant_id' => $variant->id,
                'product_name' => $productName,
                'sale_price' => $variant->sale_price,
                'attribute_value_id' => $attributeValueIds,
            ];
        }

        return response()->json($result);
    }
    public function getUnitConversions($productId)
    {
        $product = Product::query()
            ->where('id', $productId)
            ->with([
                'unitConversions' => function ($query) {
                    $query->select('id', 'product_id', 'from_unit_id', 'to_unit_id', 'conversion_factor')
                        ->with([
                            'toUnit' => function ($query) {
                                $query->select('id', 'name');
                            }
                        ]);
                }
            ])
            ->select('id')
            ->firstOrFail();

        $result = $product->unitConversions->map(function ($conversion) {
            return [
                'id' => $conversion->id,
                'product_id' => $conversion->product_id,
                'from_unit_id' => $conversion->from_unit_id,
                'to_unit_id' => $conversion->to_unit_id,
                'to_unit_name' => $conversion->toUnit ? $conversion->toUnit->name : 'Unknown',
                'conversion_factor' => $conversion->conversion_factor,
            ];
        });

        return response()->json($result);
    }
    public function searchCustomer(Request $request)
    {
        $searchTerm = $request->input('searchCustomer', '');
        $customers = Customer::query()
            ->where('name', 'LIKE', "%{$searchTerm}%")
            ->get([
                'id',
                'name',
                'phone',
                'province',
                'district',
                'ward'
            ]);
        return response()->json($customers);
    }

    public function createSaleOrder(array $data)
    {
        try {
            DB::beginTransaction();
            $addressComponents = [
                $data['address_detail'] ?? '',
                $data['ward'] ?? '',
                $data['district'] ?? '',
                $data['province'] ?? '',
            ];
            $addressDelivery = implode(', ', array_filter($addressComponents, fn($value) => !is_null($value) && $value !== '')) ?: null;
            Log::info('Address components:', $addressComponents);
            Log::info('Address delivery:', ['value' => $addressDelivery]);
            $newSaleOrder = [
                'customer_id' => $data['customer_id'],
                'order_date' => now(),
                'status' => 'pending',
                'total_amount' => $data['total_amount'],
                'address_delivery' => $addressDelivery,
            ];

            $saleOrder = $this->handleModel->create($newSaleOrder);
            if (!$saleOrder) {
                throw new \Exception('Không thể tạo đơn hàng');
            }
            Log::info('Sale Order created with ID: ' . $saleOrder->id);
            foreach ($data['items'] as $item) {
                $inventory = Inventory::where('product_variant_id', $item['product_variant_id'])
                    ->first();

                if (!$inventory) {
                    $productVariant = ProductVariant::where('id', $item['product_variant_id'])
                        ->with(['product', 'attributes'])
                        ->first();
                    $productName = $productVariant ? $productVariant->product->name : 'Unknown';
                    $variantNames = $productVariant ? $productVariant->attributes->pluck('name')->join(' - ') : '';
                    $fullName = $variantNames ? "{$productName} - {$variantNames}" : $productName;
                    throw new \Exception("Sản phẩm {$fullName} không tồn tại trong kho.");
                }

                $quantityRequested = $item['quantity'] * ($item['useCustomUnit'] ? $item['conversionFactor'] : 1);
                if ($quantityRequested > $inventory->quantity_on_hand) {
                    $productVariant = ProductVariant::where('id', $item['product_variant_id'])
                        ->with(['product', 'attributes'])
                        ->first();
                    $productName = $productVariant ? $productVariant->product->name : 'Unknown';
                    $variantNames = $productVariant ? $productVariant->attributes->pluck('name')->join(' - ') : '';
                    $fullName = $variantNames ? "{$productName} - {$variantNames}" : $productName;
                    throw new \Exception("Số lượng yêu cầu cho sản phẩm {$fullName} vượt quá số lượng trong kho (còn {$inventory->quantity_on_hand}).");
                }
                $this->saleOrderItem->create([
                    'sales_order_id' => $saleOrder->id,
                    'product_variant_id' => $item['product_variant_id'],
                    'quantity_ordered' => $item['quantity'],
                    'unit_id' => $item['useCustomUnit'] && $item['selectedUnitId'] ? $item['selectedUnitId'] : $item['defaultUnitId'],
                    'unit_price' => $item['price'] * ($item['useCustomUnit'] ? $item['conversionFactor'] : 1),
                    'subtotal' => $item['quantity'] * ($item['price'] * ($item['useCustomUnit'] ? $item['conversionFactor'] : 1)),
                    'quantity_shipped' => $item['quantity'],
                ]);
                $inventory->update([
                    'quantity_on_hand' => $inventory->quantity_on_hand - $quantityRequested
                ]);
            }
            $customer = Customer::find($data['customer_id']);
            if ($customer) {
                $updateData = [];
                if (!empty($data['province']) && $customer->province !== $data['province']) {
                    $updateData['province'] = $data['province'];
                }
                if (!empty($data['district']) && $customer->district !== $data['district']) {
                    $updateData['district'] = $data['district'];
                }
                if (!empty($data['ward']) && $customer->ward !== $data['ward']) {
                    $updateData['ward'] = $data['ward'];
                }
                if (!empty($updateData)) {
                    $customer->update($updateData);
                    Log::info('Updated customer address:', ['customer_id' => $customer->id, 'updated_fields' => $updateData]);
                }
            } else {
                Log::warning('Customer not found for ID: ' . $data['customer_id']);
            }

            DB::commit();
            return $saleOrder;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Lỗi tạo đơn hàng: ' . $th->getMessage());
            return ['error' => $th->getMessage()];
        }
    }

    public function getInventoryQuantity($productVariantId)
    {
        $inventory = Inventory::where('product_variant_id', $productVariantId)
            ->first();
        return $inventory ? $inventory->quantity_on_hand : 0;
    }
    public function rejectOrder($orderId)
    {
        try {
            DB::beginTransaction();

            // Tìm sale_order
            $saleOrder = $this->handleModel->find($orderId);
            if (!$saleOrder) {
                throw new \Exception("Đơn hàng xuất {$orderId} không tồn tại.");
            }

            // Lấy danh sách sale_order_items
            $items = $this->saleOrderItem->where('sales_order_id', $orderId)->get();

            // Khôi phục inventory
            foreach ($items as $item) {
                $inventory = Inventory::where('product_variant_id', $item->product_variant_id)
                    ->first();

                if ($inventory) {
                    // Tính số lượng khôi phục dựa trên quantity_ordered và conversion_factor
                    $productVariant = ProductVariant::where('id', $item->product_variant_id)
                        ->with(['product', 'attributes'])
                        ->first();
                    $productName = $productVariant ? $productVariant->product->name : 'Unknown';
                    $variantNames = $productVariant ? $productVariant->attributes->pluck('name')->join(' - ') : '';
                    $fullName = $variantNames ? "{$productName} - {$variantNames}" : $productName;
                    $conversionFactor = 1;
                    $unitConversion = ProductUnitConversion::where('product_id', $productVariant->product_id)
                        ->where('to_unit_id', $item->unit_id)
                        ->first();
                    if ($unitConversion) {
                        $conversionFactor = $unitConversion->conversion_factor;
                    }

                    $quantityToRestore = $item->quantity_ordered * $conversionFactor;

                    $inventory->update([
                        'quantity_on_hand' => $inventory->quantity_on_hand + $quantityToRestore
                    ]);
                    Log::info("Khôi phục tồn kho cho sản phẩm {$fullName}: +{$quantityToRestore}");
                } else {
                    Log::warning("Không tìm thấy inventory cho product_variant_id {$item->product_variant_id}");
                }
            }
            $this->saleOrderItem->where('sales_order_id', $orderId)->delete();
            Log::info("Đã xóa sale_order_items cho đơn hàng {$orderId}");
            $saleOrder->delete();
            Log::info("Đã xóa đơn hàng xuất {$orderId}");
            DB::commit();
            return ['success' => true, 'message' => "Đã từ chối và xóa đơn hàng xuất {$orderId} thành công."];
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Lỗi khi từ chối đơn hàng: ' . $th->getMessage());
            return ['error' => "Lỗi khi từ chối đơn hàng: {$th->getMessage()}"];
        }
    }
    public function approveOrder($orderId)
    {
        try {
            DB::beginTransaction();

            $saleOrder = $this->handleModel->find($orderId);
            if (!$saleOrder) {
                throw new \Exception("Đơn hàng xuất {$orderId} không tồn tại.");
            }

            if ($saleOrder->status !== 'pending') {
                throw new \Exception("Đơn hàng xuất {$orderId} không ở trạng thái chờ duyệt.");
            }

            $saleOrder->update(['status' => 'shipped']);
            Log::info("Đã duyệt đơn hàng xuất {$orderId} sang trạng thái shipped.");

            DB::commit();
            return ['success' => true, 'message' => "Đã duyệt đơn hàng xuất {$orderId} thành công."];
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Lỗi khi duyệt đơn hàng: ' . $th->getMessage());
            return ['error' => "Lỗi khi duyệt đơn hàng: {$th->getMessage()}"];
        }
    }
}