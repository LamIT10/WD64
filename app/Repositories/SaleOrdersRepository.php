<?php

namespace App\Repositories;

use App\Events\NotificationCreated;
use App\Models\Customer;
use App\Models\CustomerTransaction;
use App\Models\Inventory;
use App\Models\Notification;
use App\Models\Product;
use App\Models\ProductUnitConversion;
use App\Models\ProductVariant;
use App\Models\Rank;
use App\Models\SaleOrder;
use App\Models\SaleOrderItem;
use App\Models\Unit;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                            'subtotal',
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
                    'pay_after',
                    'note'
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
                $order->encrypted_id = encrypt($order->id);
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
            ->where('status_product', 1)
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
                $query->select('id', 'credit_percent');
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
            $actor = Auth::user();
            app(NotificationService::class)->notifyAll(
                'order_created',
                'Đơn hàng mới',
                "Đơn hàng #{$saleOrder->code} đã được tạo thành công bởi {$actor->name}.",
                [
                    'order_id' => $saleOrder->id,
                    'order_code' => $saleOrder->code,
                    'action_url' => "/admin/sale-orders?sale_order_id={$saleOrder->id}"
                ]
            );
            app(NotificationService::class)->create(
                'order_created',
                'Đơn hàng mới',
                "Đơn hàng #{$saleOrder->code} đã được tạo thành công.",
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


            $saleOrder = $this->handleModel->find($orderId);
            if (!$saleOrder) {
                throw new \Exception("Đơn hàng xuất {$orderId} không tồn tại.");
            }


            $items = $this->saleOrderItem->where('sales_order_id', $orderId)->get();


            foreach ($items as $item) {
                $inventory = Inventory::where('product_variant_id', $item->product_variant_id)
                    ->first();

                if ($inventory) {

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



            $actor = Auth::user();
            app(NotificationService::class)->notifyAll(
                'order_rejected',
                'Đơn hàng bị từ chối',
                "Đơn hàng #{$orderId} đã bị từ chối bởi {$actor->name}. Lý do: {$rejectReason}",
                [
                    'order_id' => $orderId,
                    'reject_reason' => $rejectReason,
                    'action_url' => "/admin/sale-orders"
                ]
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
            $customer = Customer::find($saleOrder->customer_id);
            if ($customer) {
                $customer->total_spent += $pay_before;

                $MaxMinTotalSpentRank = $this->rank->latest('min_total_spent')->first();
                $MinMinTotalSpentRank = $this->rank->oldest('min_total_spent')->first();
                $AllMinTotalSpentRanks = $this->rank->orderBy('min_total_spent')->get();

                if ($customer->total_spent >= $MaxMinTotalSpentRank->min_total_spent) {
                    $customer->rank_id = $MaxMinTotalSpentRank->id;
                } else if ($customer->total_spent == $MinMinTotalSpentRank->min_total_spent) {
                    $customer->rank_id = $MinMinTotalSpentRank->id;
                } else if ($customer->total_spent > $MinMinTotalSpentRank->min_total_spent  && $customer->total_spent < $MaxMinTotalSpentRank->min_total_spent) {
                    $previousRank = $MinMinTotalSpentRank;
                    foreach ($AllMinTotalSpentRanks as $rank) {
                        if ($customer->total_spent < $rank->min_total_spent) {
                            $customer->rank_id = $previousRank->id;
                            break;
                        }
                        $previousRank = $rank;
                    }
                }
                $customer->save();
            }

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
                "Đơn hàng #{$saleOrder->code} đã được duyệt thành công.",
                ['order_id' => $saleOrder->id]
            );



            $actor = Auth::user();
            app(NotificationService::class)->notifyAll(
                'order_approved',
                'Đơn hàng đã được duyệt',
                "Đơn hàng #{$saleOrder->code} đã được duyệt bởi {$actor->name} và chuyển sang trạng thái shipped",
                [
                    'order_id' => $saleOrder->id,
                    'order_code' => $saleOrder->code,
                    'pay_before' => $pay_before,
                    'action_url' => "/admin/sale-orders?sale_order_id={$saleOrder->id}"
                ]
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
            $creditDiscount = $customer->rank ? $customer->rank->credit_percent : 0;
            $maxDebt = $saleOrder->total_amount * ($creditDiscount / 100);
            $minPayTotal = $saleOrder->total_amount - $maxDebt;

            $totalPaid = ($saleOrder->pay_before ?? 0) + $pay_after;
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
            if ($totalPaid < $minPayTotal) {
                throw new \Exception(
                    "Bạn phải thanh toán tối thiểu " . number_format($minPayTotal - $saleOrder->pay_before, 0, ',', '.') . " VND. Hạn mức nợ tối đa của bạn là " . number_format($maxDebt, 0, ',', '.') . " VND."
                );
            }

            if ($pay_after > $remainingAmount) {
                throw new \Exception("Số tiền thanh toán sau ({$pay_after} VND) không được vượt quá số tiền còn lại ({$remainingAmount} VND).");
            }

            $saleOrder->update([
                'status' => 'completed',
                'pay_after' => $pay_after
            ]);


            $customer->total_spent += $pay_after;


            if ($customer->total_spent >= $MaxMinTotalSpentRank->min_total_spent) {
                $customer->rank_id = $MaxMinTotalSpentRank->id;
            } else if ($customer->total_spent == $MinMinTotalSpentRank->min_total_spent) {
                $customer->rank_id = $MinMinTotalSpentRank->id;
            } else if ($customer->total_spent > $MinMinTotalSpentRank->min_total_spent  && $customer->total_spent < $MaxMinTotalSpentRank->min_total_spent) {
                $previousRank = $MinMinTotalSpentRank;
                foreach ($AllMinTotalSpentRanks as $rank) {
                    if ($customer->total_spent < $rank->min_total_spent) {
                        $customer->rank_id = $previousRank->id;
                        break;
                    }
                    $previousRank = $rank;
                }
            }
            $customer->save();
            Log::info("Đã xác nhận hoàn thành đơn hàng xuất {$orderId} với pay_after = {$pay_after}.");

            DB::commit();


            app(NotificationService::class)->create(
                'order_completed',
                'Đơn hàng đã hoàn thành',
                "Đơn hàng #{$saleOrder->code} đã được xác nhận hoàn thành.",
                ['order_id' => $saleOrder->id]
            );


            $actor = Auth::user();
            app(NotificationService::class)->notifyAll(
                'order_completed',
                'Đơn hàng đã hoàn thành',
                "Đơn hàng #{$saleOrder->code} đã được xác nhận hoàn thành bởi {$actor->name} với số tiền thanh toán sau: " . number_format($pay_after) . " VNĐ",
                [
                    'order_id' => $saleOrder->id,
                    'order_code' => $saleOrder->code,
                    'pay_after' => $pay_after,
                    'total_amount' => $saleOrder->total_amount,
                    'action_url' => "/admin/sale-orders?sale_order_id={$saleOrder->id}"
                ]
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


    public function getOrderForInvoice($orderId)
    {
        return $this->handleModel
            ->with([
                'customer' => function ($query) {
                    $query->select('id', 'name', 'phone', 'email', 'province', 'ward', 'rank_id')
                        ->with(['rank' => function ($query) {
                            $query->select('id', 'name', 'discount_percent');
                        }]);
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
                'pay_after',
                'note'
            )
            ->findOrFail($orderId);
    }
    public function returnOrder($orderId, $returnReason)
    {
        $order = $this->handleModel->find($orderId);
        if (!$order) return ['error' => 'Không tìm thấy đơn hàng'];
        if ($order->status !== 'shipped') return ['error' => 'Chỉ hoàn hàng khi đang giao hàng'];
        $order->update([
            'status' => 'returning',
            'note' => '[RETURN] ' . $returnReason,
        ]);
        app(NotificationService::class)->create(
            'order_returning',
            'Đơn hàng đang hoàn',
            "Đơn hàng #{$order->code} đang được hoàn hàng. Lý do: {$returnReason}",
            ['order_id' => $order->id]
        );
        $actor = Auth::user();
        app(NotificationService::class)->notifyAll(
            'order_returning',
            'Đơn hàng đang hoàn',
            "Đơn hàng #{$order->code} đang được hoàn hàng. Lý do: {$returnReason} (Thực hiện bởi {$actor->name})",
            [
                'order_id' => $order->id,
                'return_reason' => $returnReason,
                'action_url' => "/admin/sale-orders?sale_order_id={$order->id}"
            ]
        );
        return ['success' => true];
    }


    public function returnedOrder($orderId)
    {
        $order = $this->handleModel->with('items')->find($orderId);
        if (!$order) return ['error' => 'Không tìm thấy đơn hàng'];
        if ($order->status !== 'returning') return ['error' => 'Chỉ xác nhận khi đang hoàn hàng'];
        foreach ($order->items as $item) {
            $inventory = Inventory::where('product_variant_id', $item->product_variant_id)->first();
            if ($inventory) {
                $inventory->update([
                    'quantity_on_hand' => $inventory->quantity_on_hand + $item->quantity_ordered
                ]);
            }
        }
        $order->update(['status' => 'returned']);
        app(NotificationService::class)->create(
            'order_returned',
            'Đơn hàng đã hoàn hàng thành công',
            "Đơn hàng #{$order->code} đã hoàn hàng thành công.",
            ['order_id' => $order->id]
        );

        $actor = Auth::user();
        app(NotificationService::class)->notifyAll(
            'order_returned',
            'Đơn hàng đã hoàn hàng thành công',
            "Đơn hàng #{$order->code} đã hoàn hàng thành công bởi {$actor->name}.",
            [
                'order_id' => $order->id,
                'action_url' => "/admin/sale-orders?sale_order_id={$order->id}"
            ]
        );
        return ['success' => true];
    }
}
