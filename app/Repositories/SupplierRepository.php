<?php

namespace App\Repositories;

use App\Models\ProductVariant;
use App\Models\Supplier;
use App\Models\SupplierProductVariant;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SupplierRepository extends BaseRepository
{
    protected $purchaseOrderRepository;
    public function __construct(Supplier $supplier)
    {
        $this->handleModel = $supplier;
    }

    public function getList($data)
    {
        $query = $this->handleModel::with([
            'purchaseOrders' => function ($query) {
                return $query->select(['id', 'supplier_id']);
            },
            'purchaseOrders.goodReceipts' => function ($query) {
                return $query->select(['id', 'purchase_order_id', 'code', 'receipt_date', 'note', 'status', 'approved_by', 'created_by', 'total_amount']);
            },
            'purchaseOrders.goodReceipts.supplierTransaction' => function ($query) {
                return $query->select(['id', 'goods_receipt_id', 'paid_amount', 'transaction_date', 'credit_due_date', 'description']);
            },
        ])
        ->select(['id', 'name', 'contact_person', 'phone', 'email', 'address', 'current_debt'])
        ->selectRaw('
            (SELECT SUM(total_amount) 
             FROM good_receipts 
             WHERE good_receipts.purchase_order_id IN (
                 SELECT id FROM purchase_orders WHERE purchase_orders.supplier_id = suppliers.id
             )) - 
            COALESCE((SELECT SUM(paid_amount) 
             FROM supplier_transactions 
             WHERE supplier_transactions.goods_receipt_id IN (
                 SELECT id FROM good_receipts 
                 WHERE good_receipts.purchase_order_id IN (
                     SELECT id FROM purchase_orders WHERE purchase_orders.supplier_id = suppliers.id
                 )
             )), 0) as debt
        ');
        $filters = [
            'search' => [
                'name' => $data->search ?? "",
            ]
        ];
        $query = $this->filterData($query, $filters);
        $query->orderBy('id', 'desc');
        return $query->paginate(20);;
    }
    public function createData($data = [])
    {

        try {
            $newSupplier = [];
            DB::beginTransaction();
            $newSupplier['name'] = $data['name'] ?? null;
            $newSupplier['phone'] = $data['phone'] ?? null;
            $newSupplier['address'] = $data['address'] ?? null;
            $newSupplier['email'] = $data['email'] ?? null;
            $newSupplier['contact_person'] = $data['contact_person'] ?? null;

            $supplier = $this->handleModel->create($newSupplier);
            DB::commit();
            return $supplier;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("Lỗi khi tạo nhà cung cấp, " . $th->getMessage());
            return $this->returnError("Lỗi khi tạo nhà cung cấp");
        }
    }
    public function updateData($id, $data)
    {
        try {
            $supplier = $this->handleModel->find($id);
            if (!$supplier) {
                return $this->returnError("Không tìm thấy nhà cung cấp");
            }
            DB::beginTransaction();
            $supplierUpdate = [];
            $supplierUpdate['name'] = $data['name'] ?? null;
            $newSupplier['phone'] = $data['phone'] ?? null;
            $newSupplier['address'] = $data['address'] ?? null;
            $newSupplier['email'] = $data['email'] ?? null;
            $newSupplier['contact_person'] = $data['contact_person'] ?? null;

            $supplier->update($supplierUpdate);
            DB::commit();
            return $supplier;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("Lỗi khi cập nhật nhà cung cấp, " . $th->getMessage());
            return $this->returnError("Lỗi khi cập nhật nhà cung cấp");
        }
    }
    public function deleteData($id)
    {
        try {
            DB::beginTransaction();
            $supplier = $this->handleModel->find($id);
            if (!$supplier) {
                return $this->returnError("Không tìm thấy nhà cung cấp");
            }
            $supplier->delete();
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("Lỗi khi xoá nhà cung cấp, " . $th->getMessage());
            return $this->returnError("Lỗi khi xoá nhà cung cấp");
        }
    }
    public function listSelectSupplier()
    {
        $data = $this->handleModel->select(['id', 'name'])->get()->toArray();
        return $data;
    }
    public function getProductBySupplierId($id)
    {
        try {
            $data = [];
            $query = Supplier::with([
                'variants' => function ($query) {
                    $query->with(['product.category', 'attributes']);
                }
            ])->findOrFail($id);
            if (!$query) {
                throw new Exception("Không tìm thấy nhà cung cấp");
            }


            $data['supplier'] = [
                'id' => $query->id,
                'name' => $query->name
            ];
            $groupedVariants = $query->variants->groupBy(function ($variant) {
                return $variant->product_id ?? 'Không rõ sản phẩm';
            });

            $listVariantByProduct = Supplier::with(['supplierVariants'])->find(($id));
            $data['listVariants'] = ProductVariant::with(["product", 'attributes', 'attributes.attribute'])->whereNotIn('Id', $listVariantByProduct->supplierVariants->pluck("product_variant_id"))->get();


            // Chuẩn hóa dữ liệu cho frontend
            $data['products'] = $groupedVariants->map(function ($variants, $productId) {
                $product = $variants->first()->product;
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'category' => $product->category ? ['name' => $product->category->name] : null,
                    'product_variants' => $variants->map(function ($variant) {
                        $att = [
                            'att_value' => "",
                            "att" => "",
                        ];

                        foreach ($variant->attributes as $key => $item) {
                            $att['att_value'] = $key != 0  ? $att['att_value'] . " - " . $item->name : $item->name;
                            $att['att'] = $key != 0  ?  $att['att'] . " - " . $item->attribute->name : $item->attribute->name;
                        }
                        return [
                            'id' => $variant->id,
                            'code' => $variant->code,
                            'barcode' => $variant->barcode,
                            'sale_price' => $variant->sale_price, // Lấy từ product_variants
                            'attributes' => $att
                        ];
                    })->toArray()
                ];
            })->values()->toArray();
            return $data;
        } catch (Exception $e) {
            return $this->returnError($e->getMessage());
        }
    }
    public function handleCreateSupplierProduct($data)
    {
        try {
            DB::beginTransaction();
            $newObj = [];
            $newObj['cost_price'] = $data['cost_price'];
            $newObj['min_order_quantity'] = $data['min_order_quantity'];
            $newObj['product_variant_id'] = $data['id'];
            $newObj['supplier_id'] = $data['supplier_id'];
            $newObj = SupplierProductVariant::create($newObj);
            if (!$newObj) {
                throw new Exception("Có lỗi khi thêm biên thể cho nhà cung cấp");
            }
            DB::commit();
            return $newObj;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('Lỗi khi thêm sản phẩm cho nhà cung cấp: ' . $th->getMessage());
            return $this->returnError($th->getMessage());
        }
    }
}
