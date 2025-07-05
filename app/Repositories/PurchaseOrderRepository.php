<?php

namespace App\Repositories;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\ProductUnitConversion;
use App\Models\ProductVariant;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurchaseOrderRepository extends BaseRepository
{
    protected $productRepository;
    public function __construct(PurchaseOrder $model)
    {
        $this->handleModel = $model;
    }
    public function getList($request)
    {
        $query = $this->handleModel->with([
            'supplier' => function ($query) {
                $query->select(['id', 'name']);
            },
            'user' => function ($query) {
                $query->select(['id', 'name']);
            },
            'items' => function ($query) {
                $query->select(['id', 'purchase_order_id', 'product_variant_id', 'quantity_ordered', 'quantity_received', 'unit_price', 'unit_id', 'subtotal']);
            },
            'items.productVariant' => function ($query) {
                $query->select(['id', 'product_id']);
            },
            'items.productVariant.product' => function ($query) {
                $query->select(['id', 'name', 'default_unit_id']);
            },
            'items.productVariant.attributes' => function ($query) {
                $query->select(['*']);
            },
            'items.unit' => function ($query) {
                $query->select(['id', 'name']);
            },
            'goodReceipts' => function ($query) {
                $query->select(['id', 'code', 'purchase_order_id']);
            },
        ]);
        if (isset($request->order_status)) {
            switch ($request->order_status) {
                case 0:
                    $query->where('order_status', 0);
                    break;
                case 1:
                    $query->where('order_status', 1);
                    break;
                case 2:
                    $query->where('order_status', 2);
                    break;
                case 3:
                    $query->where('order_status', 3);
                    break;
                default:
                    break;
            }
        }
        $query->orderByDesc('id');

        $purchaseList = $query->paginate(10);
        return $purchaseList;
    }
    public function getDataForcreate()
    {
        $data = [];
        $data['products'] = Product::all();
        return $data;
    }
    public function getVariants($idProduct)
    {
        $variants = Product::with([
            'productVariants.suppliers' => function ($query) {
                $query->select(['*']);
            },
            'productVariants.attributes' => function ($query) {
                $query->select(['*']);
            },
            'unitDefault' => function ($query) {
                $query->select(['id', 'name']);
            },
            'unitConversions' => function ($query) {
                $query->select(['*']);
            },
            'unitConversions.fromUnit' => function ($query) {
                $query->select(['id', 'name']);
            },
            'unitConversions.toUnit' => function ($query) {
                $query->select(['id', 'name']);
            },
        ])
            ->select(['id', 'name', 'default_unit_id'])
            ->find($idProduct);
        return $variants;
    }
    public function getSupplierAndUnit($idVariant)
    {
        $data = ProductVariant::with([
            'suppliers' => function ($query) {
                $query->select(['*']);
            },
            'product' => function ($query) {
                $query->select(['id', 'name', 'default_unit_id']);
            },
            'product.units' => function ($query) {
                $query->select(['id', 'name']);
            },
            'product.unitConversions' => function ($query) {
                $query->select(['*']);
            },
            'product.unitConversions.fromUnit' => function ($query) {
                $query->select(['id', 'name']);
            },
            'product.unitConversions.toUnit' => function ($query) {
                $query->select(['id', 'name']);
            },
        ])
            ->find($idVariant);
        return $data;
    }
    public function store($data)
    {
        try {
            $listPurchaseOrderItems = $data['orders'] ?? [];
            DB::beginTransaction();
            if (count($listPurchaseOrderItems) <= 0) {
                DB::rollBack();
                return $this->returnError('Vui lòng chọn đơn hàng');
            }
            foreach ($listPurchaseOrderItems as $key => $value) {
                $newPurchaseOrderData = [];
                $newPurchaseOrderData['supplier_id'] = $value['supplier_id'] ?? null;
                $newPurchaseOrderData['total_amount'] = $value['subtotal'] ?? null;
                $newPurchaseOrderData['user_id'] = Auth::user()->id ?? 1;
                $newPurchaseOrderData['order_date'] = $value['expected_date'] ?? null;
                $newPurchaseOrderData['status'] = $value['status'] ?? 0;

                $newPurchaseOrder = $this->handleModel->create($newPurchaseOrderData);
                if (!$newPurchaseOrder) {
                    DB::rollBack();
                    return $this->returnError('Lỗi khi tạo đơn hàng, vui lòng thử lại');
                }
                $newPurchaseOrderItem = $value['items'] ?? [];
                foreach ($newPurchaseOrderItem as $item) {
                    $item['purchase_order_id'] = $newPurchaseOrder->id;
                    $item['product_variant_id'] = $item['variant_id'] ?? null;
                    $item['quantity_ordered'] = $item['quantity'] ?? 0;
                    $item['unit_id'] = $item['unit_id'] ?? null;
                    $item['unit_price'] = $item['price'] ?? 0;
                    $item['subtotal'] = $item['unit_price'] * $item['quantity_ordered'] ?? 0;
                    $item['quantity_received'] = 0;
                    $newPurchaseOrderItem = PurchaseOrderItem::create($item);
                    if (!$newPurchaseOrderItem) {
                        DB::rollBack();
                        return $this->returnError('Lỗi khi tạo đơn hàng, vui lòng thử lại');
                    }
                }
            }
            DB::commit();
            return $newPurchaseOrder;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Lỗi khi tạo mới yêu cầu nhập: ' . $th->getMessage());
            return $this->returnError('Lỗi hệ thống, vui lòng thử lại sau');
        }
    }
    public function approve($id)
    {
        try {
            DB::beginTransaction();
            $purchaseOrder = $this->handleModel->find($id);
            if (!$purchaseOrder) {
                return $this->returnError('Đơn hàng không tồn tại');
            }
            if ($purchaseOrder->order_status == 1) {
                DB::rollBack();
                return $this->returnError('Đơn hàng đã được phê duyệt trước đó');
            }
            $orderApproved = [];
            $orderApproved['order_status'] = 1;
            $orderApproved['approved_at'] = Carbon::now();
            $purchaseOrder->update($orderApproved);
            // đổi thành mảng key value dạng số lượng => id variant để foreach rồi cập nhật số lượng transit
            $listItemVariant = PurchaseOrderItem::where('purchase_order_id', $id)->get(['product_variant_id', 'quantity_ordered', 'unit_id']);
            foreach ($listItemVariant as $itemVariant) {
                $productVariantId = $itemVariant->product_variant_id;
                $quantity = $itemVariant->quantity_ordered;
                $orderUnitId = $itemVariant->unit_id;

                $inventory = Inventory::where('product_variant_id', $productVariantId)->first();
                if (!$inventory) {
                    DB::rollBack();
                    return $this->returnError('Lỗi khi phê duyệt đơn hàng, tồn kho không hợp lệ');
                }

                $productID = ProductVariant::find($productVariantId)->product_id;
                $unitDefault = Product::find($productID)->default_unit_id;

                if ($unitDefault == $orderUnitId) {
                    $quantityLast = $quantity;
                } else {
                    $convert = ProductUnitConversion::where('product_id', $productID)
                        ->where('from_unit_id', $unitDefault)
                        ->where('to_unit_id', $orderUnitId)
                        ->first();
                    if (!$convert) {
                        DB::rollBack();
                        return $this->returnError('Không tìm thấy tỉ lệ quy đổi đơn vị');
                    }
                    $quantityLast = intval($quantity * $convert->conversion_factor);
                }

                $inventory->update([
                    'quantity_in_transit' => $inventory->quantity_in_transit + $quantityLast,
                ]);
            }

            DB::commit();
            return $purchaseOrder;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Lỗi khi phê duyệt đơn hàng: ' . $th->getMessage());
            return $this->returnError('Lỗi hệ thống, vui lòng thử lại sau');
        }
    }
    public function getPurchaseDetail($id)
    {
        $data = [];
        $purchase = $this->handleModel
            ->with([
                'items' => function ($query) {
                    $query->select(['*']);
                },
                'items.unit' => function ($query) {
                    $query->select(['id', 'name', 'symbol']);
                },
                'supplier' => function ($query) {
                    $query->select('id', 'name');
                },
                'user' => function ($query) {
                    $query->select('id', 'name');
                },
                'items.productVariant' => function ($query) {
                    $query->select(['id', 'product_id']);
                },
                'items.productVariant.product' => function ($query) {
                    $query->select(['id', 'name', 'default_unit_id']);
                },
                'items.productVariant.attributes' => function ($query) {
                    $query->select(['*']);
                },
                'items.unit' => function ($query) {
                    $query->select('id', 'name');
                }
            ])->find($id);

        $listUser = User::select('id', 'name')->get();
        $data = [
            'purchase' => $purchase,
            'user' => $listUser
        ];
        return $data;
    }
}
