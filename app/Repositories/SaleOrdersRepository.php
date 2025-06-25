<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Models\Product;
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
    public function getList()
    {
        try {
            return  $this->handleModel->select(
                'sale_orders.id',
                'customers.id as customer_id',
                'customers.name',
                'customers.address',
                'sale_orders.status',
                'sale_orders.total_amount',
                'sale_orders.address_delivery'
            )->leftJoin('customers', 'sale_orders.customer_id', '=', 'customers.id')
                ->get();
        } catch (\Throwable $th) {
            return  Log::error('Lỗi lấy toàn bộ danh sách đơn xuất: ' . $th->getMessage());
        }
    }
    public function searchProduct(Request $request)
    {
        $searchTerm = $request->input('searchProduct', '');

        // Truy vấn sản phẩm theo tên
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

        // Biến đổi dữ liệu trả về
        $result = [];

        foreach ($products as $product) {
            $defaultUnit = Unit::find($product->default_unit_id);
            foreach ($product->productVariants as $variant) {
                // Lấy danh sách tên giá trị thuộc tính
                $attributeNames = $variant->attributes->pluck('name')->implode('-');
                $attributeValueIds = $variant->attributes->pluck('id')->toArray();

                // Tạo tên hiển thị: product_name + attribute_names
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

            // Ghép địa chỉ giao hàng
            $addressComponents = [
                $data['address_detail'] ?? '',
                $data['ward'] ?? '',
                $data['district'] ?? '',
                $data['province'] ?? '',
            ];
            $addressDelivery = implode(', ', array_filter($addressComponents, fn($value) => !is_null($value) && $value !== '')) ?: null;
            Log::info('Address components:', $addressComponents);
            Log::info('Address delivery:', ['value' => $addressDelivery]);

            // Tạo sale_order
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

            // Tạo sale_order_items
            foreach ($data['items'] as $item) {
                $this->saleOrderItem->create([
                    'sales_order_id' => $saleOrder->id,
                    'product_variant_id' => $item['product_variant_id'],
                    'quantity_ordered' => $item['quantity'],
                    'unit_id' => $item['useCustomUnit'] && $item['selectedUnitId'] ? $item['selectedUnitId'] : $item['defaultUnitId'],
                    'unit_price' => $item['price'] * ($item['useCustomUnit'] ? $item['conversionFactor'] : 1),
                    'subtotal' => $item['quantity'] * ($item['price'] * ($item['useCustomUnit'] ? $item['conversionFactor'] : 1)),
                    'quantity_shipped' => $item['quantity'],
                ]);
            }

            // Cập nhật thông tin địa chỉ cho khách hàng nếu có thay đổi hoặc trường hiện tại là null
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
            return ['error' => 'Lỗi tạo đơn hàng: ' . $th->getMessage()];
        }
    }
}