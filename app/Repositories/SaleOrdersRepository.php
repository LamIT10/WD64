<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Models\CustomerTransaction;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\ProductUnitConversion;
use App\Models\ProductVariant;
use App\Models\Rank;
use App\Models\SaleOrder;
use App\Models\SaleOrderItem;
use App\Models\Unit;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class SaleOrdersRepository extends BaseRepository
{
    protected $product;
    protected $saleOrderItem;
    protected $rank;
    public function __construct(SaleOrder $saleOrder, Product $product, SaleOrderItem $saleOrderItem, Rank $rank)
    {
        $this->handleModel = $saleOrder;
        $this->saleOrderItem = $saleOrderItem;
        $this->product = $product;
        $this->rank = $rank;
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
                    'code',
                    'customer_id',
                    'order_date',
                    'status',
                    'total_amount',
                    'address_delivery',
                    'created_at',
                    'pay_before',
                    'pay_after'
                );

            if ($request->has('status') && !empty($request->status)) {
                $query->where('status', $request->status);
            }
            if ($request->has('customer') && !empty($request->customer)) {
                $query->whereHas('customer', function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->input('customer') . '%');
                });
            }
            if ($request->has('order_date') && !empty($request->order_date)) {
                $query->whereDate('created_at', $request->order_date);
            }

            $perPage = $request->input('per_page', 10); // Mặc định 10 bản ghi mỗi trang
            $orders = $query->orderBy('created_at', 'desc')->paginate($perPage)->through(function ($order) {
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
            ->with(['rank' => function ($query) {
                $query->select('id', 'discount_percent');
            }])
            ->get([
                'id',
                'name',
                'phone',
                'province',
                'ward',
                'rank_id',
            ]);
        return response()->json($customers);
    }

    public function createSaleOrder(array $data)
    {
        try {
            DB::beginTransaction();

            $customer = Customer::findOrFail($data['customer_id']);
            $creditTerm = $customer->credit_term ?? 30;
            $creditDueDate = now()->addDays($creditTerm);

            $addressComponents = [
                $data['address_detail'] ?? '',
                $data['ward'] ?? '',
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
                'credit_due_date' => $creditDueDate,
                'address_delivery' => $addressDelivery,
                'code' => $this->autoAddSaleOrderCode(),
            ];

            $saleOrder = $this->handleModel->create($newSaleOrder);
            if (!$saleOrder) {
                throw new \Exception('Không thể tạo đơn hàng');
            }

            Log::info('Sale Order created with ID: ' . $saleOrder->id);

            foreach ($data['items'] as $item) {
                $inventory = Inventory::where('product_variant_id', $item['product_variant_id'])->first();

                if (!$inventory) {
                    $productVariant = ProductVariant::where('id', $item['product_variant_id'])->with(['product', 'attributes'])->first();
                    $productName = $productVariant ? $productVariant->product->name : 'Unknown';
                    $variantNames = $productVariant ? $productVariant->attributes->pluck('name')->join(' - ') : '';
                    $fullName = $variantNames ? "{$productName} - {$variantNames}" : $productName;
                    throw new \Exception("Sản phẩm {$fullName} không tồn tại trong kho.");
                }

                $quantityRequested = $item['quantity'] * ($item['useCustomUnit'] ? $item['conversionFactor'] : 1);
                if ($quantityRequested > $inventory->quantity_on_hand) {
                    $productVariant = ProductVariant::where('id', $item['product_variant_id'])->with(['product', 'attributes'])->first();
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
                    'quantity_reserved' => $inventory->quantity_reserved + $quantityRequested
                ]);
            }

            // Cập nhật thông tin địa chỉ khách hàng nếu thay đổi
            if ($customer) {
                $updateData = [];
                if (!empty($data['province']) && $customer->province !== $data['province']) {
                    $updateData['province'] = $data['province'];
                }
                if (!empty($data['ward']) && $customer->ward !== $data['ward']) {
                    $updateData['ward'] = $data['ward'];
                }
                if (!empty($updateData)) {
                    $customer->update($updateData);
                    Log::info('Updated customer address:', ['customer_id' => $customer->id, 'updated_fields' => $updateData]);
                }
            }

            DB::commit();
            app(NotificationService::class)->create(
                'order_created',
                'Đơn hàng mới',
                "Đơn hàng #{$saleOrder->id} đã được tạo thành công.",
                ['order_id' => $saleOrder->id]
            );
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
        return $inventory ? ($inventory->quantity_on_hand - $inventory->quantity_reserved) : 0;
    }
    public function rejectOrder($orderId, $rejectReason = null)
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
                        'quantity_reserved' => $inventory->quantity_reserved - $quantityToRestore
                    ]);
                    Log::info("Khôi phục tồn kho cho sản phẩm {$fullName}: +{$quantityToRestore}");
                } else {
                    Log::warning("Không tìm thấy inventory cho product_variant_id {$item->product_variant_id}");
                }
            }
            $saleOrder->update(['status' => 'cancelled', 'note' => $rejectReason]);
            Log::info("Đã xóa sale_order_items cho đơn hàng {$orderId}");
            Log::info("Đã xóa đơn hàng xuất {$orderId}");
            DB::commit();
            app(NotificationService::class)->create(
                'order_rejected',
                'Đơn hàng bị từ chối',
                "Đơn hàng #{$orderId} đã bị từ chối. Lý do: {$rejectReason}",
                ['order_id' => $orderId]
            );
            return ['success' => true, 'message' => "Đã từ chối và xóa đơn hàng xuất {$orderId} thành công."];
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Lỗi khi từ chối đơn hàng: ' . $th->getMessage());
            return ['error' => "Lỗi khi từ chối đơn hàng: {$th->getMessage()}"];
        }
    }
    public function approveOrder($orderId, $pay_before)
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

            if ($pay_before > $saleOrder->total_amount) {
                throw new \Exception("Số tiền thanh toán trước ({$pay_before} VND) không được vượt quá tổng tiền đơn hàng ({$saleOrder->total_amount} VND).");
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
                        'quantity_on_hand' => $inventory->quantity_on_hand - $inventory->quantity_reserved,
                        'quantity_reserved' => $inventory->quantity_reserved - $quantityToRestore
                    ]);
                    Log::info("Khôi phục tồn kho cho sản phẩm {$fullName}: +{$quantityToRestore}");
                } else {
                    Log::warning("Không tìm thấy inventory cho product_variant_id {$item->product_variant_id}");
                }
            }

            $saleOrder->update([
                'status' => 'shipped',
                'pay_before' => $pay_before
            ]);
            Log::info("Đã duyệt đơn hàng xuất {$orderId} sang trạng thái shipped với pay_before = {$pay_before}.");

            DB::commit();
            app(NotificationService::class)->create(
                'order_approved',
                'Đơn hàng đã được duyệt',
                "Đơn hàng #{$saleOrder->id} đã được duyệt thành công.",
                ['order_id' => $saleOrder->id]
            );
            return ['success' => true, 'message' => "Đã duyệt đơn hàng xuất {$orderId} thành công."];
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Lỗi khi duyệt đơn hàng: ' . $th->getMessage());
            return ['error' => "Lỗi khi duyệt đơn hàng: {$th->getMessage()}"];
        }

        if ($saleOrder->status !== 'pending') {
            throw new \Exception("Đơn hàng xuất {$orderId} không ở trạng thái chờ duyệt.");
        }
    }


    public function completeOrder($orderId, $pay_after, $customerId)
    {
        try {
            DB::beginTransaction();
            $customer = Customer::findOrFail($customerId);
            $saleOrder = $this->handleModel->find($orderId);
            $MaxMinTotalSpentRank = $this->rank->latest('min_total_spent')->first();
            $MinMinTotalSpentRank = $this->rank->oldest('min_total_spent')->first();
            $AllMinTotalSpentRanks = $this->rank->select('min_total_spent', 'id')->get();
            if (!$saleOrder) {
                throw new \Exception("Đơn hàng xuất {$orderId} không tồn tại.");
            }

            if ($saleOrder->status !== 'shipped') {
                throw new \Exception("Đơn hàng xuất {$orderId} không ở trạng thái đang giao hàng.");
            }

            $remainingAmount = $saleOrder->total_amount - ($saleOrder->pay_before ?? 0);
            if ($pay_after > $remainingAmount) {
                throw new \Exception("Số tiền thanh toán sau ({$pay_after} VND) không được vượt quá số tiền còn lại ({$remainingAmount} VND).");
            }

            $saleOrder->update([
                'status' => 'completed',
                'pay_after' => $pay_after
            ]);
            // Thêm tổng tiền đã thanh toán vào tích số tiền đã tiêu của khách hàng
            $totalSpent = $this->handleModel
                ->where('customer_id', $customerId)
                ->where('status', 'completed')
                ->whereRaw('total_amount = pay_before + pay_after')
                ->sum('total_amount');
            if ($saleOrder->total_amount - $saleOrder->pay_before == $pay_after) {
                $customer->update(
                    [
                        'total_spent' => $totalSpent
                    ]
                );
                // Cập nhật rank của khách hàng khi đơn hàng hoàn thành và không nợ
                if ($MaxMinTotalSpentRank->min_total_spent <= $customer->total_spent) {
                    $customer->update(
                        [
                            'rank_id' => $MaxMinTotalSpentRank->id
                        ]
                    );
                } else if ($customer->total_spent == $MinMinTotalSpentRank->min_total_spent) {
                    $customer->update(
                        [
                            'rank_id' => $MinMinTotalSpentRank->id
                        ]
                    );
                } else if ($customer->total_spent > $MinMinTotalSpentRank->min_total_spent  && $customer->total_spent < $MaxMinTotalSpentRank->min_total_spent) {
                    $previousRank = $MinMinTotalSpentRank;
                    foreach ($AllMinTotalSpentRanks as $rank) {
                        if ($customer->total_spent < $rank->min_total_spent) {
                            $customer->update(
                                [
                                    'rank_id' => $previousRank->id
                                ]
                            );
                            break;
                        }
                        $previousRank = $rank;
                    }
                }
            }
            Log::info("Đã xác nhận hoàn thành đơn hàng xuất {$orderId} với pay_after = {$pay_after}.");

            DB::commit();
            app(NotificationService::class)->create(
                'order_completed',
                'Đơn hàng đã hoàn thành',
                "Đơn hàng #{$saleOrder->id} đã được xác nhận hoàn thành.",
                ['order_id' => $saleOrder->id]
            );
            return ['success' => true, 'message' => "Đã xác nhận hoàn thành đơn hàng xuất {$orderId} thành công."];
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Lỗi khi xác nhận hoàn thành đơn hàng: ' . $th->getMessage());
            return ['error' => "Lỗi khi xác nhận hoàn thành đơn hàng: {$th->getMessage()}"];
        }
    }
    public function generateQR($id, Request $request)
    {
        $order = $this->handleModel->findOrFail($id);
        $amount = max(0, $order->total_amount - ($order->pay_before ?? 0) - ($order->pay_after ?? 0));
        $body = [
            'accountNo' => env('VIETQR_ACCOUNT_NO'),
            'accountName' => env('VIETQR_ACCOUNT_NAME'),
            'acqId' => env('VIETQR_ACQ_ID'),
            'amount' => $amount,
            'addInfo' => "Thanh toan don hang {$order->id}",
            'format' => 'text',
            'template' => env('VIETQR_TEMPLATE', 'compact'),
        ];
        $response = Http::withHeaders([
            'x-client-id' => env('VIETQR_CLIENT_ID'),
            'x-api-key' => env('VIETQR_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.vietqr.io/v2/generate', $body);
        if ($response->failed()) {
            Log::error('VietQR HTTP failed', ['status' => $response->status(), 'body' => $response->body()]);
            return response()->json(['error' => 'Lỗi tạo QR: ' . $response->body()], 500);
        }

        $data = $response->json();
        Log::info('VietQR Response Data', ['data' => $data]);  // Log mảng $data để xem có key 'data' không
        return [
            'qrDataURL' => $data['data']['qrDataURL'],
            'qrCode' => $data['data']['qrCode'],
        ];
    }
    public function getPageOfOrder($orderId, $perPage = 10)
    {
        $query = $this->handleModel->newQuery();


        // Sắp xếp giống index()
        $query->orderBy('created_at', 'desc');

        // Lấy danh sách id theo thứ tự
        $ids = $query->pluck('id')->toArray();
        $index = array_search($orderId, $ids);

        if ($index === false) return 1; // Không tìm thấy, mặc định trang 1

        return intval(floor($index / $perPage)) + 1;
    }
    public function autoAddSaleOrderCode()
    {
        $lastExportCode = $this->handleModel
            ->where('code', 'LIKE', 'DX-%')
            ->orderByDesc('id')
            ->value('code');

        if ($lastExportCode) {
            $lastExportNumber = (int) str_replace('DX-', '', $lastExportCode);
        } else {
            $lastExportNumber = 0;
        }

        $autoExportCode = 'DX-' . str_pad($lastExportNumber + 1, 6, '0', STR_PAD_LEFT);

        return $autoExportCode;
    }
}
