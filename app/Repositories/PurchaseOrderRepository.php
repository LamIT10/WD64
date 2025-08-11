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
        if ($request->filled('order_status')) {
            $query->where('order_status', $request->integer('order_status'));
        }
        if ($request->filled('supplier')) {
            $query->whereHas('supplier', fn($s) => $s->where('name', 'like', '%' . $request->supplier . '%'));
        }
        if ($request->filled('code')) {
            $query->where('code', 'like', '%' . $request->code . '%');
        }
        if ($request->filled('start') || $request->filled('end')) {
            $start = $request->start ? Carbon::parse($request->start)->startOfDay() : Carbon::minValue();
            $end   = $request->end   ? Carbon::parse($request->end)->endOfDay()   : Carbon::maxValue();
            $query->whereBetween('created_at', [$start, $end]);
        }
        $query->orderByDesc('id');

        $purchaseList = $query->paginate(3)->withQueryString();
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
                $newPurchaseOrderData['code'] = $this->autoAddPurchaseCode();

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
                    if ($item['quantity_ordered'] <= 0) {
                        DB::rollBack();
                        return $this->returnError('Số lượng nhập phải lớn hơn không');
                    }
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
    public function cancel($data, $id)
    {
        try {
            DB::beginTransaction();
            $purchaseOrder = $this->handleModel->find($id);
            if (!$purchaseOrder) {
                return $this->returnError('Đơn hàng không tồn tại');
            }
            if ($purchaseOrder->order_status != 0) {
                DB::rollBack();
                return $this->returnError('Đơn đã duyệt, không thể từ chối');
            }
            if ($purchaseOrder->order_status == 4) {
                DB::rollBack();
                return $this->returnError('Đơn hàng đang ở trạng thái từ chối');
            }
            $orderApproved = [];
            $orderApproved['reason'] = $data['reason'];
            $orderApproved['order_status'] = 4;
            $purchaseOrder->update($orderApproved);

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
                'items.productVariant.product.unitConversions' => function ($query) {
                    $query->select(['id', 'name']);
                },
                'items.productVariant.product.unitDefault' => function ($query) {
                    $query->select(['id', 'name']);
                },
                'items.productVariant.product.unitConversions.toUnit' => function ($query) {
                    $query->select(['id', 'name']);
                },
                'items.productVariant.attributes' => function ($query) {
                    $query->select(['*']);
                },
                'items.unit' => function ($query) {
                    $query->select('id', 'name');
                },
                'items.productVariant' => function ($query) {
                    $query->select(['id', 'product_id']);
                },
                'items.productVariant.product' => function ($query) {
                    $query->select(['id', 'name', 'default_unit_id']);
                },
                'items.productVariant.product.unitConversions' => function ($query) {
                    $query->select(['*']);
                },
                'items.productVariant.product.unitConversions.fromUnit' => function ($query) {
                    $query->select(['id', 'name']);
                },
                'items.productVariant.product.unitConversions.toUnit' => function ($query) {
                    $query->select(['id', 'name']);
                },
                'items.productVariant.attributes' => function ($query) {
                    $query->select(['*']);
                },
                'items.unit' => function ($query) {
                    $query->select('id', 'name');
                }
            ])->find($id);

        $listUser = User::select('id', 'name')->get();
        $listProduct = Product::get();
        $data = [
            'purchase' => $purchase,
            'user' => $listUser,
            'product' => $listProduct
        ];
        return $data;
    }
    public function update($data, $id)
    {
        try {
            DB::beginTransaction();
            $purchase = $this->handleModel->find($id);
            if (!$purchase) {
                DB::rollBack();
                return $this->returnError('Đơn hàng không tồn tại');
            }
            $dataUpdate = [];
            $dataUpdate['user_id'] = $data['user_id'];
            $dataUpdate['order_date'] = $data['order_date'];
            $dataUpdate['total_amount'] = $data['total_amount'];

            $purchase->update($dataUpdate);
            foreach ($data['items'] as $key => $value) {
                $orderItem = PurchaseOrderItem::find($value['id']);
                if (!$orderItem) {
                    DB::rollBack();
                    return $this->returnError('Có lỗi khi cập nhật sản phẩm trong đơn');
                }
                $dataItemUpdate = [];

                $dataItemUpdate['quantity_ordered'] = $value['quantity_ordered'];
                $dataItemUpdate['unit_id'] = $value['unit_id'];
                $dataItemUpdate['unit_price'] = $value['unit_price'];
                $dataItemUpdate['subtotal'] = $value['subtotal'];

                $orderItem->update($dataItemUpdate);
            }
            DB::commit();
            return $purchase;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Lỗi khi cập nhật đơn hàng');
            return $this->returnError('Lỗi hệ thống, vui lòng thử lại sau');
        }
    }
    public function autoAddPurchaseCode()
    {
        $lastExportCode = $this->handleModel
            ->where('code', 'LIKE', 'DN-%')
            ->orderByDesc('id')
            ->value('code');

        if ($lastExportCode) {
            $lastExportNumber = (int) str_replace('DN-', '', $lastExportCode);
        } else {
            $lastExportNumber = 0;
        }

        $autoExportCode = 'DN-' . str_pad($lastExportNumber + 1, 6, '0', STR_PAD_LEFT);

        return $autoExportCode;
    }
    public function end($id)
    {
        try {
            $purchaseOrder = $this->handleModel->find($id);
            if (!$purchaseOrder) {
                return $this->returnError('Đơn hàng không tồn tại');
            }
            if ($purchaseOrder->order_status == 0 && $purchaseOrder->order_status == 1) {
                return $this->returnError('Đơn hàng chưa nhập, không thể kết thúc');
            }
            if ($purchaseOrder->order_status == 3) {
                return $this->returnError('Đơn hàng đã hoàn thành');
            }
            if ($purchaseOrder->order_status == 4) {
                return $this->returnError('Đơn hàng đã bị từ chối');
            }
            DB::beginTransaction();
            $purchaseOrder->update(['order_status' => 3]);
            DB::commit();
            return $purchaseOrder;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Lỗi khi kết thúc đơn hàng: ' . $th->getMessage());
            return $this->returnError('Lỗi hệ thống, vui lòng thử lại sau');
        }
    }
}
