<?php

namespace App\Repositories;

use App\Models\Attribute;
use App\Models\Inventory;
use App\Models\InventoryLocation;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Supplier;
use App\Models\SupplierProductVariant;
use App\Models\Unit;
use App\Models\WarehouseZone;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductRepository extends BaseRepository
{

    protected $handleModel;
    protected $repositoryCategory;
    public function __construct(Product $product, CategoryRepository $category)
    {
        $this->handleModel = $product;
        $this->repositoryCategory = $category;
    }

    public function getAll(array $filters = [], $perPage = 20)
    {
        $query = $this->handleModel::with([
            'category',
            'images',
            'productVariants' => function ($query) {
                $query->with([
                    'product',
                    'attributes',
                    'inventory',
                    'inventoryLocations.zone',
                    'supplierVariants'
                ]);
            },
            'unitConversions.fromUnit',
            'unitConversions.toUnit',
            'defaultUnit',
        ])->where('status_product', 1);

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['code'])) {
            $query->where('code', 'like', '%' . $filters['code'] . '%');
        }

        if (!empty($filters['stock_status'])) {
            $query->whereHas('productVariants', function ($variantQuery) use ($filters) {
                $variantQuery->with('inventory');

                if ($filters['stock_status'] === 'out_of_stock') {
                    $variantQuery->whereDoesntHave('inventory')
                        ->orWhereHas('inventory', function ($q) {
                            $q->where('quantity_on_hand', '==', 0);
                        });
                }

                if ($filters['stock_status'] === 'low_stock') {
                    $variantQuery->where(function ($q) {
                        $q->whereHas('inventory', function ($inv) {
                            $inv->select('product_variant_id')
                                ->groupBy('product_variant_id')
                                ->havingRaw('SUM(quantity_on_hand) <= MIN(min_stock)')
                                ->havingRaw('SUM(quantity_on_hand) !=    0');
                        });
                    });
                }

                if ($filters['stock_status'] === 'normal') {
                    $variantQuery->whereHas('inventory', function ($inv) {
                        $inv->select('product_variant_id')
                            ->groupBy('product_variant_id')
                            ->havingRaw('SUM(quantity_on_hand) > MIN(min_stock)');
                    });
                }
            });
        }
        if(isset($filters['get_count']) && $filters['get_count'] == true) {
            return $query->count();
        }
        return $query->paginate($perPage)->withQueryString();
    }

    public function findById($id)
    {
        return $this->handleModel::with(['category'])->find($id);
    }
    public function generateProductCode()
    {
        $today = now()->format('Ymd');
        $countToday = Product::whereDate('created_at', today())->count() + 1;

        return 'PRO-' . $today . str_pad($countToday, 3, '0', STR_PAD_LEFT);
    }

    public function generateVariantCode()
    {
        $today = now()->format('Ymd');
        $countToday = ProductVariant::whereDate('created_at', today())->count() + 1;

        return 'VAR-' . $today . str_pad($countToday, 3, '0', STR_PAD_LEFT);
    }
    public function generateNumericBarcode()
    {
        return str_pad((string) random_int(0, 99999999999999999), 17, '0', STR_PAD_LEFT);
    }
    public function getCreateData()
    {
        $attributes = Attribute::with('values')->get();

        $attributeValues = [];
        foreach ($attributes as $attribute) {
            $attributeValues[$attribute->id] = $attribute->values;
        }

        return [
            'categories' => $this->repositoryCategory->getHierarchicalCategories(),
            'units' => Unit::all(),
            'attributes' => $attributes->makeHidden('values'),
            'attributeValues' => $attributeValues,
            'suppliers' => Supplier::all(['id', 'name']),
            'warehouseZones' => WarehouseZone::all(['id', 'name']),
        ];
    }

    public function getProductsBySupplier($supplierId, $perPage = 15)
    {
        return $this->handleModel::with([
            'category',
            'images',
            'productVariants' => function ($query) {
                $query->with([
                    'attributes',
                    'inventory',
                    'inventoryLocations.zone',
                    'supplierVariants'
                ]);
            },
            'unitConversions.fromUnit',
            'unitConversions.toUnit',
        ])
            ->whereHas('productVariants.supplierVariants', function ($query) use ($supplierId) {
                $query->where('supplier_id', $supplierId);
            })
            ->paginate($perPage);
    }
    public function store($data)
    {
        try {
            DB::beginTransaction();

            // Log dữ liệu nhận được để debug
            Log::info('Dữ liệu tạo sản phẩm:', ['data' => $data]);

            // 1. Tạo sản phẩm chính
            $dataProduct = array_filter([
                'name' => $data['name'] ?? null,
                'code' => $data['code'] ?? null,
                'description' => $data['description'] ?? null,
                'min_stock' => $data['min_stock'] ?? 0,
                'category_id' => $data['category_id'] ?? null,
                'production_date' => $data['production_date'] ?? null,
                'expiration_date' => $data['expiration_date'] ?? null,
                'default_unit_id' => $data['base_unit_id'] ?? null,
            ], fn($value) => !is_null($value));

            $product = $this->handleModel->create($dataProduct);
            Log::info('Tạo sản phẩm chính thành công:', ['product_id' => $product->id]);

            // 2. Tạo đơn vị quy đổi
            if (!empty($data['unit_conversions'])) {
                foreach ($data['unit_conversions'] as $conv) {
                    if (!empty($conv['to_unit_id'])) {
                        $product->unitConversions()->create([
                            'from_unit_id' => $data['base_unit_id'],
                            'to_unit_id' => $conv['to_unit_id'],
                            'conversion_factor' => $conv['conversion_factor'],
                        ]);
                    }
                }
                Log::info('Tạo đơn vị quy đổi thành công:', ['product_id' => $product->id]);
            }

            // 3. Tạo biến thể hoặc sản phẩm đơn giản
            if (!empty($data['variants'])) {
                foreach ($data['variants'] as $variant) {
                    foreach ($variant['combinations'] as $combo) {
                        // Kiểm tra attribute_value_ids
                        if (!empty($combo['attribute_value_ids'])) {
                            $validIds = \App\Models\AttributeValue::whereIn('id', $combo['attribute_value_ids'])->pluck('id')->toArray();
                            if (count($validIds) !== count($combo['attribute_value_ids'])) {
                                throw new \Exception('Một số attribute_value_ids không hợp lệ.');
                            }
                        }

                        $productVariant = $product->productVariants()->create([
                            'code' => $combo['code'] ?? null,
                            'barcode' => $combo['barcode'] ?? null,
                            'sale_price' => $combo['sale_price'] ?? 0,
                        ]);

                        foreach ($combo['attribute_value_ids'] ?? [] as $valueId) {
                            $productVariant->attributes()->attach($valueId);
                        }

                        $productVariant->inventory()->create([
                            'quantity_on_hand' => $combo['quantity_on_hand'] ?? 0,
                            'status' => 'available',
                            'unit_id' => $data['base_unit_id'],
                            'warehouse_zone_id' => $combo['warehouse_zone_id'] ?? null,
                        ]);

                        if (!empty($combo['warehouse_zone_id'])) {
                            InventoryLocation::create([
                                'product_variant_id' => $productVariant->id,
                                'zone_id' => $combo['warehouse_zone_id'],
                                'custom_location_name' => $combo['custom_location_name'] ?? null,
                            ]);
                        }

                        if (!empty($combo['supplier_ids']) && is_array($combo['supplier_ids'])) {
                            foreach ($combo['supplier_ids'] as $supplierId) {
                                SupplierProductVariant::create([
                                    'supplier_id' => $supplierId,
                                    'product_variant_id' => $productVariant->id,
                                    'cost_price' => 0,
                                    'min_order_quantity' => null,
                                ]);
                            }
                        }

                        Log::info('Tạo biến thể thành công:', ['variant_id' => $productVariant->id]);
                    }
                }
            } else {
                // Sản phẩm đơn giản
                $simpleVariant = $product->productVariants()->create([
                    'barcode' => $data['simple_barcode'] ?? null,
                    'sale_price' => $data['simple_sale_price'] ?? 0,
                ]);

                $simpleVariant->inventory()->create([
                    'quantity_on_hand' => $data['simple_quantity'] ?? 0,
                    'status' => 'available',
                    'unit_id' => $data['base_unit_id'],
                    'warehouse_zone_id' => $data['warehouse_zone_id'] ?? null,
                ]);

                if (!empty($data['warehouse_zone_id'])) {
                    InventoryLocation::create([
                        'product_variant_id' => $simpleVariant->id,
                        'zone_id' => $data['warehouse_zone_id'],
                        'custom_location_name' => $data['custom_location_name'] ?? null,
                    ]);
                }

                if (!empty($data['supplier_ids']) && is_array($data['supplier_ids'])) {
                    foreach ($data['supplier_ids'] as $supplierId) {
                        SupplierProductVariant::create([
                            'supplier_id' => $supplierId,
                            'product_variant_id' => $simpleVariant->id,
                            'cost_price' => 0,
                            'min_order_quantity' => null,
                        ]);
                    }
                }

                Log::info('Tạo sản phẩm đơn giản thành công:', ['variant_id' => $simpleVariant->id]);
            }

            // 4. Lưu ảnh
            if (!empty($data['images'])) {
                $currentImageCount = 0; // Không có ảnh cũ trong create
                Log::info('Số lượng ảnh hiện tại:', ['count' => $currentImageCount]);

                foreach ($data['images'] as $image) {
                    if ($image instanceof UploadedFile && $currentImageCount < 4) {
                        $path = $image->store('products', 'public');
                        $product->images()->create([
                            'url' => $path,
                        ]);
                        $currentImageCount++;
                        Log::info('Tải lên ảnh mới:', ['path' => $path]);
                    }
                }
            }

            DB::commit();
            Log::info('Tạo sản phẩm thành công:', ['product_id' => $product->id]);
            return $product;
        } catch (\Throwable $th) {
            Log::error('Lỗi khi tạo sản phẩm: ' . $th->getMessage(), ['data' => $data]);
            DB::rollBack();
            return $this->returnError($th->getMessage());
        }
    }
    public function show($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            return response()->json([
                'error' => true,
                'message' => 'ID sản phẩm không hợp lệ',
            ], 400);
        }

        $product = $this->handleModel::with([
            'category',
            'images',
            'defaultUnit',
            'unitConversions.toUnit',
            'productVariants.inventory',
            'productVariants.attributes',
            'productVariants.inventoryLocations.zone',
            'productVariants.supplierVariants.supplier',
        ])->findOrFail($id);

        return [
            'id' => $product->id,
            'name' => $product->name,
            'code' => $product->code,
            'description' => $product->description ?? '',
            'min_stock' => $product->min_stock ?? 0,
            'category' => $product->category ? [
                'id' => $product->category->id,
                'name' => $product->category->name,
            ] : null,
            'production_date' => $product->production_date,
            'expiration_date' => $product->expiration_date,
            'default_unit' => $product->defaultUnit ? [
                'id' => $product->defaultUnit->id,
                'name' => $product->defaultUnit->name,
                'symbol' => $product->defaultUnit->symbol,
            ] : null,
            'images' => $product->images->map(fn($img) => [
                'id' => $img->id,
                'path' => $img->url, // Ensure this matches frontend expectations (e.g., /storage/...)
            ])->values()->toArray(),
            'unit_conversions' => $product->unitConversions->map(fn($conv) => [
                'to_unit_id' => $conv->to_unit_id,
                'to_unit' => $conv->toUnit ? [
                    'id' => $conv->toUnit->id,
                    'name' => $conv->toUnit->name,
                    'symbol' => $conv->toUnit->symbol,
                ] : null,
                'conversion_factor' => $conv->conversion_factor,
            ])->values()->toArray(),
            'product_variants' => $product->productVariants->map(fn($variant) => [
                'id' => $variant->id,
                'code' => $variant->code,
                'barcode' => $variant->barcode ?? null,
                'sale_price' => $variant->sale_price ?? 0,
                'inventory' => $variant->inventory->first() ? [
                    'quantity_on_hand' => $variant->inventory->first()->quantity_on_hand ?? 0,
                ] : [],
                'attributes' => $variant->attributes->map(fn($attr) => [
                    'id' => $attr->id,
                    'name' => $attr->name,
                ])->values()->toArray(),
                'inventory_locations' => $variant->inventoryLocations->map(fn($loc) => [
                    'zone' => $loc->zone ? [
                        'id' => $loc->zone->id,
                        'name' => $loc->zone->name,
                    ] : null,
                    'custom_location_name' => $loc->custom_location_name ?? '',
                ])->values()->toArray(),
                'supplier_variants' => $variant->supplierVariants->map(fn($sv) => [
                    'supplier' => $sv->supplier ? [
                        'id' => $sv->supplier->id,
                        'name' => $sv->supplier->name,
                        'phone' => $sv->supplier->phone ?? '',
                    ] : null,
                ])->values()->toArray(),
            ])->values()->toArray(),
            'supplier_variants' => $product->productVariants->flatMap->supplierVariants->map(fn($sv) => [
                'supplier' => $sv->supplier ? [
                    'id' => $sv->supplier->id,
                    'name' => $sv->supplier->name,
                    'phone' => $sv->supplier->phone ?? '',
                ] : null,
            ])->unique('supplier.id')->values()->toArray(),
            'inventory_locations' => $product->productVariants->flatMap->inventoryLocations->map(fn($loc) => [
                'zone' => $loc->zone ? [
                    'id' => $loc->zone->id,
                    'name' => $loc->zone->name,
                ] : null,
                'custom_location_name' => $loc->custom_location_name ?? '',
            ])->unique(fn($loc) => $loc['zone']['id'] . '-' . $loc['custom_location_name'])->values()->toArray(),
        ];
    }

    public function getEditData($id)
    {
        try {
            $product = $this->handleModel::with([
                'category',
                'images',
                'defaultUnit',
                'unitConversions.fromUnit',
                'unitConversions.toUnit',
                'productVariants.inventory',
                'productVariants.attributes',
                'productVariants.inventoryLocations',
                'productVariants.supplierVariants.supplier'
            ])->find($id);

            if (!$product) {
                return $this->returnError("Không tìm thấy sản phẩm");
            }

            $attributes = Attribute::with('values')->get();
            // 1. Lấy toàn bộ attribute_value_id đang được dùng trong các variant
            $usedAttributeValueIds = collect();
            foreach ($product->productVariants as $variant) {
                foreach ($variant->attributes as $attrVal) {
                    $usedAttributeValueIds->push($attrVal->id);
                }
            }
            $usedAttributeValueIds = $usedAttributeValueIds->unique()->values();
            // 2. Gắn is_locked vào các attribute value
            $attributeValues = [];
            foreach ($attributes as $attribute) {
                $attributeValues[$attribute->id] = $attribute->values->map(function ($val) use ($usedAttributeValueIds) {
                    $val->is_locked = $usedAttributeValueIds->contains($val->id);
                    return $val;
                });
            }

            $productData = [
                'id' => $product->id,
                'name' => $product->name,
                'code' => $product->code,
                'description' => $product->description,
                'min_stock' => $product->min_stock,
                'category_id' => $product->category_id,
                'production_date' => $product->production_date,
                'expiration_date' => $product->expiration_date,
                'base_unit_id' => $product->default_unit_id,
                'unit_conversions' => $product->unitConversions->map(function ($conv) {
                    return [
                        'to_unit_id' => $conv->to_unit_id,
                        'conversion_factor' => $conv->conversion_factor
                    ];
                })->toArray(),
                'images' => $product->images->map(function ($image) {
                    return [
                        'id' => $image->id,
                        'url' => Storage::url($image->url)
                    ];
                })->toArray(),
            ];

            if ($product->productVariants->isNotEmpty() && $product->productVariants->first()->attributes->isNotEmpty()) {
                $variants = $product->productVariants->map(function ($variant) {
                    $combinationData = [];
                    $key = $variant->attributes->pluck('id')->join('-');
                    $combinationData[$key] = [
                        'code' => $variant->code,
                        'barcode' => $variant->barcode,
                        'sale_price' => $variant->sale_price,
                        'quantity_on_hand' => $variant->inventory->first()->quantity_on_hand ?? 0,
                        'supplier_ids' => $variant->supplierVariants->pluck('supplier_id')->toArray(),
                        'warehouse_zone_id' => $variant->inventoryLocations->first()->zone_id ?? null,
                        'custom_location_name' => $variant->inventoryLocations->first()->custom_location_name ?? null
                    ];

                    return [
                        'code' => $variant->code,
                        'barcode' => $variant->barcode,
                        'sale_price' => $variant->sale_price,
                        'unit_id' => $variant->inventory->first()->unit_id ?? null,
                        'attributes' => $variant->attributes->groupBy('attribute_id')->map(function ($group) {
                            return [
                                'attribute_id' => $group->first()->attribute_id,
                                'attribute_value_ids' => $group->pluck('id')->toArray()
                            ];
                        })->values()->toArray(),
                        'combinationData' => $combinationData
                    ];
                })->toArray();

                $productData['variants'] = $variants;
            } else {
                $variant = $product->productVariants->first();
                $productData['simple_sale_price'] = $variant->sale_price ?? 0;
                $productData['simple_quantity'] = $variant->inventory->first()->quantity_on_hand ?? 0;
                $productData['simple_barcode'] = $variant->barcode ?? null;
                $productData['supplier_ids'] = $variant->supplierVariants->pluck('supplier_id')->toArray();
                $productData['warehouse_zone_id'] = $variant->inventoryLocations->first()->zone_id ?? null;
                $productData['custom_location_name'] = $variant->inventoryLocations->first()->custom_location_name ?? null;
            }

            return [
                'product' => $productData,
                'categories' => $this->repositoryCategory->getHierarchicalCategories(),
                'units' => Unit::all(),
                'attributes' => $attributes->makeHidden('values'),
                'attributeValues' => $attributeValues,
                'suppliers' => Supplier::all(['id', 'name']),
                'warehouseZones' => WarehouseZone::all(['id', 'name']),
            ];
        } catch (Exception $e) {
            Log::error("Lỗi khi lấy dữ liệu chỉnh sửa sản phẩm: " . $e->getMessage());
            return $this->returnError("Không tìm thấy sản phẩm");
        }
    }

    public function update($id, $data)
    {
        try {
            DB::beginTransaction();

            $product = $this->handleModel::findOrFail($id);

            // Log dữ liệu nhận được để debug
            Log::info('Dữ liệu cập nhật sản phẩm:', ['data' => $data]);

            // 1. Cập nhật sản phẩm chính
            $dataProduct = array_filter([
                'name' => $data['name'] ?? null,
                'code' => $data['code'] ?? null,
                'description' => $data['description'] ?? null,
                'min_stock' => $data['min_stock'] ?? 0,
                'category_id' => $data['category_id'] ?? null,
                'production_date' => $data['production_date'] ?? null,
                'expiration_date' => $data['expiration_date'] ?? null,
                'default_unit_id' => $data['base_unit_id'] ?? null,
            ], fn($value) => !is_null($value));

            $product->update($dataProduct);

            // 2. Đồng bộ đơn vị quy đổi
            $existingUnitConversions = $product->unitConversions()->get()->keyBy(function ($item) {
                return $item->to_unit_id;
            })->toArray();

            $newUnitConversions = [];
            if (!empty($data['unit_conversions'])) {
                foreach ($data['unit_conversions'] as $conv) {
                    if (!empty($conv['to_unit_id'])) {
                        $newUnitConversions[$conv['to_unit_id']] = [
                            'from_unit_id' => $data['base_unit_id'],
                            'to_unit_id' => $conv['to_unit_id'],
                            'conversion_factor' => $conv['conversion_factor'],
                        ];
                    }
                }
            }

            foreach ($newUnitConversions as $toUnitId => $convData) {
                if (isset($existingUnitConversions[$toUnitId])) {
                    $product->unitConversions()->where('to_unit_id', $toUnitId)->update($convData);
                    unset($existingUnitConversions[$toUnitId]);
                } else {
                    $product->unitConversions()->create($convData);
                }
            }

            if (!empty($existingUnitConversions)) {
                $product->unitConversions()->whereIn('to_unit_id', array_keys($existingUnitConversions))->delete();
            }

            // 3. Đồng bộ biến thể hoặc sản phẩm đơn giản
            $existingVariants = $product->productVariants()->get()->keyBy('id')->toArray();

            if (!empty($data['variants'])) {
                $newVariantIds = [];
                foreach ($data['variants'] as $variant) {
                    foreach ($variant['combinations'] as $combo) {
                        $existingVariant = null;
                        foreach ($existingVariants as $v) {
                            if ($v['barcode'] === ($combo['barcode'] ?? null) || $v['code'] === ($combo['code'] ?? null)) {
                                $existingVariant = $v;
                                break;
                            }
                        }

                        if ($existingVariant) {
                            $variantId = $existingVariant['id'];
                            $product->productVariants()->where('id', $variantId)->update([
                                'code' => $combo['code'] ?? null,
                                'barcode' => $combo['barcode'] ?? null,
                                'sale_price' => $combo['sale_price'] ?? 0,
                            ]);

                            $productVariant = $product->productVariants()->find($variantId);
                            $productVariant->attributes()->sync($combo['attribute_value_ids'] ?? []);

                            $productVariant->inventory()->updateOrCreate(
                                ['product_variant_id' => $variantId],
                                [
                                    'quantity_on_hand' => $combo['quantity_on_hand'] ?? 0,
                                    'status' => 'available',
                                    'unit_id' => $data['base_unit_id'],
                                    'warehouse_zone_id' => $combo['warehouse_zone_id'] ?? null,
                                ]
                            );

                            if (!empty($combo['warehouse_zone_id'])) {
                                InventoryLocation::updateOrCreate(
                                    ['product_variant_id' => $variantId],
                                    [
                                        'zone_id' => $combo['warehouse_zone_id'],
                                        'custom_location_name' => $combo['custom_location_name'] ?? null,
                                    ]
                                );
                            } else {
                                InventoryLocation::where('product_variant_id', $variantId)->delete();
                            }

                            if (!empty($combo['supplier_ids'])) {
                                $existingSupplierIds = SupplierProductVariant::where('product_variant_id', $variantId)
                                    ->pluck('supplier_id')->toArray();
                                $newSupplierIds = $combo['supplier_ids'];

                                foreach ($newSupplierIds as $supplierId) {
                                    if (!in_array($supplierId, $existingSupplierIds)) {
                                        SupplierProductVariant::create([
                                            'supplier_id' => $supplierId,
                                            'product_variant_id' => $variantId,
                                            'cost_price' => 0,
                                            'min_order_quantity' => null,
                                        ]);
                                    }
                                }

                                SupplierProductVariant::where('product_variant_id', $variantId)
                                    ->whereNotIn('supplier_id', $newSupplierIds)->delete();
                            } else {
                                SupplierProductVariant::where('product_variant_id', $variantId)->delete();
                            }

                            $newVariantIds[] = $variantId;
                            unset($existingVariants[$variantId]);
                        } else {
                            $productVariant = $product->productVariants()->create([
                                'code' => $combo['code'] ?? null,
                                'barcode' => $combo['barcode'] ?? null,
                                'sale_price' => $combo['sale_price'] ?? 0,
                            ]);

                            foreach ($combo['attribute_value_ids'] ?? [] as $valueId) {
                                $productVariant->attributes()->attach($valueId);
                            }

                            $productVariant->inventory()->create([
                                'quantity_on_hand' => $combo['quantity_on_hand'] ?? 0,
                                'status' => 'available',
                                'unit_id' => $data['base_unit_id'],
                                'warehouse_zone_id' => $combo['warehouse_zone_id'] ?? null,
                            ]);

                            if (!empty($combo['warehouse_zone_id'])) {
                                InventoryLocation::create([
                                    'product_variant_id' => $productVariant->id,
                                    'zone_id' => $combo['warehouse_zone_id'],
                                    'custom_location_name' => $combo['custom_location_name'] ?? null,
                                ]);
                            }

                            if (!empty($combo['supplier_ids'])) {
                                foreach ($combo['supplier_ids'] as $supplierId) {
                                    SupplierProductVariant::create([
                                        'supplier_id' => $supplierId,
                                        'product_variant_id' => $productVariant->id,
                                        'cost_price' => 0,
                                        'min_order_quantity' => null,
                                    ]);
                                }
                            }

                            $newVariantIds[] = $productVariant->id;
                        }
                    }
                }

                $product->productVariants()->whereNotIn('id', $newVariantIds)->delete();
            } else {
                $simpleVariant = $product->productVariants()->firstOrNew([]);
                $simpleVariant->barcode = $data['simple_barcode'] ?? null;
                $simpleVariant->sale_price = $data['simple_sale_price'] ?? 0;
                $simpleVariant->save();

                $simpleVariant->inventory()->updateOrCreate(
                    ['product_variant_id' => $simpleVariant->id],
                    [
                        'quantity_on_hand' => $data['simple_quantity'] ?? 0,
                        'status' => 'available',
                        'unit_id' => $data['base_unit_id'],
                        'warehouse_zone_id' => $data['warehouse_zone_id'] ?? null,
                    ]
                );

                if (!empty($data['warehouse_zone_id'])) {
                    InventoryLocation::updateOrCreate(
                        ['product_variant_id' => $simpleVariant->id],
                        [
                            'zone_id' => $data['warehouse_zone_id'],
                            'custom_location_name' => $data['custom_location_name'] ?? null,
                        ]
                    );
                } else {
                    InventoryLocation::where('product_variant_id', $simpleVariant->id)->delete();
                }

                if (!empty($data['supplier_ids']) && is_array($data['supplier_ids'])) {
                    $existingSupplierIds = SupplierProductVariant::where('product_variant_id', $simpleVariant->id)
                        ->pluck('supplier_id')->toArray();
                    $newSupplierIds = $data['supplier_ids'];

                    foreach ($newSupplierIds as $supplierId) {
                        if (!in_array($supplierId, $existingSupplierIds)) {
                            SupplierProductVariant::create([
                                'supplier_id' => $supplierId,
                                'product_variant_id' => $simpleVariant->id,
                                'cost_price' => 0,
                                'min_order_quantity' => null,
                            ]);
                        }
                    }

                    SupplierProductVariant::where('product_variant_id', $simpleVariant->id)
                        ->whereNotIn('supplier_id', $newSupplierIds)->delete();
                } else {
                    SupplierProductVariant::where('product_variant_id', $simpleVariant->id)->delete();
                }

                $product->productVariants()->where('id', '!=', $simpleVariant->id)->delete();
            }

            // 4. Cập nhật hình ảnh
            if (!empty($data['deleted_image_ids'])) {
                Log::info('Xử lý xóa ảnh:', ['deleted_image_ids' => $data['deleted_image_ids']]);
                foreach ($data['deleted_image_ids'] as $imageId) {
                    $image = $product->images()->find($imageId);
                    if ($image) {
                        Log::info('Xóa ảnh:', ['image_id' => $imageId, 'url' => $image->url]);
                        if (Storage::disk('public')->exists($image->url)) {
                            Storage::disk('public')->delete($image->url);
                            Log::info('Xóa file thành công:', ['url' => $image->url]);
                        } else {
                            Log::warning('File không tồn tại:', ['url' => $image->url]);
                        }
                        $image->delete();
                    } else {
                        Log::warning('Không tìm thấy ảnh:', ['image_id' => $imageId]);
                    }
                }
            }

            if (!empty($data['images'])) {
                $currentImageCount = $product->images()->count();
                Log::info('Số lượng ảnh hiện tại:', ['count' => $currentImageCount]);

                foreach ($data['images'] as $image) {
                    if (isset($image['data']) && $currentImageCount < 4) { // Xử lý ảnh mới (Base64)
                        // Giải mã Base64
                        $base64String = preg_replace('#^data:image/\w+;base64,#i', '', $image['data']);
                        $imageData = base64_decode($base64String);
                        if ($imageData === false) {
                            throw new \Exception('Dữ liệu ảnh Base64 không hợp lệ.');
                        }

                        // Lưu ảnh
                        $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
                        $path = 'products/' . uniqid() . '.' . $extension;
                        Storage::disk('public')->put($path, $imageData);
                        Log::info('Tải lên ảnh mới:', ['path' => $path]);

                        // Lưu vào database
                        $product->images()->create([
                            'url' => $path,
                        ]);
                        $currentImageCount++;
                    } elseif (isset($image['id'])) { // Ảnh cũ, bỏ qua vì đã giữ trong DB
                        Log::info('Giữ ảnh cũ:', ['image_id' => $image['id']]);
                    }
                }
            }

            DB::commit();
            Log::info('Cập nhật sản phẩm thành công:', ['product_id' => $product->id]);
            return $product;
        } catch (\Throwable $th) {
            Log::error('Lỗi khi cập nhật sản phẩm: ' . $th->getMessage(), ['data' => $data]);
            DB::rollBack();
            return $this->returnError($th->getMessage());
        }
    }

    // public function destroy($id)
    // {
    //     try {
    //         DB::beginTransaction();

    //         // Tìm sản phẩm với các liên kết cần xóa
    //         $product = $this->handleModel::with([
    //             'images',
    //             'productVariants.inventory',
    //             'productVariants.inventoryLocations',
    //             'productVariants.supplierVariants',
    //             'unitConversions'
    //         ])->findOrFail($id);

    //         Log::info('Bắt đầu xóa sản phẩm:', ['product_id' => $id]);

    //         // Kiểm tra trạng thái sản phẩm (không xóa nếu có liên kết đơn hàng)
    //         foreach ($product->productVariants as $variant) {
    //             if (
    //                 $variant->purchaseOrderItems()->exists() ||
    //                 $variant->salesOrderItems()->exists() ||
    //                 $variant->receivingItems()->exists() ||
    //                 $variant->shippingItems()->exists() ||
    //                 $variant->inventoryAuditItems()->exists() ||
    //                 $variant->damagedExpiredProducts()->exists()
    //             ) {
    //                 throw new \Exception('Không thể xóa sản phẩm vì đang được sử dụng trong đơn hàng hoặc kiểm kho.');
    //             }
    //         }

    //         // 1. Xóa ảnh
    //         foreach ($product->images as $image) {
    //             if (Storage::disk('public')->exists($image->url)) {
    //                 Storage::disk('public')->delete($image->url);
    //                 Log::info('Xóa file ảnh thành công:', ['image_id' => $image->id, 'url' => $image->url]);
    //             } else {
    //                 Log::warning('File ảnh không tồn tại:', ['image_id' => $image->id, 'url' => $image->url]);
    //             }
    //             $image->delete();
    //         }

    //         // 2. Xóa các biến thể và liên kết
    //         $variantIds = $product->productVariants->pluck('id')->toArray();
    //         if (!empty($variantIds)) {
    //             // Xóa inventory, inventory_locations, supplier_variants
    //             Inventory::whereIn('product_variant_id', $variantIds)->delete();
    //             InventoryLocation::whereIn('product_variant_id', $variantIds)->delete();
    //             SupplierProductVariant::whereIn('product_variant_id', $variantIds)->delete();

    //             // Xóa các biến thể (liên kết trong product_variant_attributes tự động xóa nhờ cascade)
    //             $product->productVariants()->delete();
    //             Log::info('Xóa biến thể thành công:', ['variant_ids' => $variantIds]);
    //         }

    //         // 3. Xóa đơn vị quy đổi
    //         $product->unitConversions()->delete();
    //         Log::info('Xóa đơn vị quy đổi thành công:', ['product_id' => $id]);

    //         // 4. Xóa sản phẩm chính
    //         $product->delete();
    //         Log::info('Xóa sản phẩm chính thành công:', ['product_id' => $id]);

    //         DB::commit();
    //         Log::info('Xóa sản phẩm hoàn tất:', ['product_id' => $id]);
    //         return true;
    //     } catch (\Throwable $th) {
    //         DB::rollBack();
    //         Log::error('Lỗi khi xóa sản phẩm:', [
    //             'product_id' => $id,
    //             'error' => $th->getMessage(),
    //             'trace' => $th->getTraceAsString()
    //         ]);
    //         return $this->returnError('Lỗi khi xóa sản phẩm: ' . $th->getMessage());
    //     }
    // }


    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            $product = $this->handleModel->find($id);

            if (!$product) {
                throw new Exception('Không tìm thấy sản phẩm.');
            }
            $product->update(['status_product' => 0]);
            DB::commit();

            return $product;
        } catch (\Throwable $th) {
            Log::error('Lỗi khi cập nhật trạng thái sản phẩm:', [
                'product_id' => $id,
                'error' => $th->getMessage()
            ]);
            return $this->returnError('Lỗi khi cập nhật trạng thái sản phẩm.');
        }
    }

    public function search($search)
    {
        return $this->handleModel::select(['id', 'name'])
            ->where('name', 'like', '%' . $search . '%')
            ->orWhere('code', 'like', '%' . $search . '%')
            ->take(10)
            ->get()
            ->toArray();
    }

    public function getAvailableVariants($productId, $supplierId)
    {
        $linkedVariantIds = SupplierProductVariant::where('supplier_id', $supplierId)
            ->pluck('product_variant_id')
            ->toArray();

        return ProductVariant::where('product_id', $productId)
            ->whereNotIn('id', $linkedVariantIds)
            ->with(['attributes'])
            ->get()
            ->map(function ($variant) {
                return [
                    'id' => $variant->id,
                    'code' => $variant->code,
                    'barcode' => $variant->barcode,
                    'sale_price' => $variant->sale_price,
                    'attributes' => $variant->attributes->map(function ($attr) {
                        return [
                            'id' => $attr->id,
                            'value' => $attr->value
                        ];
                    })
                ];
            })->toArray();
    }

    public function getInactive(array $filters = [], $perPage = 20)
    {
        $query = $this->handleModel::with([
            'category',
            'images',
            'productVariants' => function ($query) {
                $query->with([
                    'attributes',
                    'inventory',
                    'inventoryLocations.zone',
                    'supplierVariants'
                ]);
            },
            'unitConversions.fromUnit',
            'unitConversions.toUnit',
            'defaultUnit',
        ])->where('status_product', 0);

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['code'])) {
            $query->where('code', 'like', '%' . $filters['code'] . '%');
        }

        if (!empty($filters['stock_status'])) {
            $query->whereHas('productVariants', function ($variantQuery) use ($filters) {
                $variantQuery->with('inventory');

                if ($filters['stock_status'] === 'out_of_stock') {
                    $variantQuery->whereDoesntHave('inventory')
                        ->orWhereHas('inventory', function ($q) {
                            $q->where('quantity_on_hand', '==', 0);
                        });
                }

                if ($filters['stock_status'] === 'low_stock') {
                    $variantQuery->where(function ($q) {
                        $q->whereHas('inventory', function ($inv) {
                            $inv->select('product_variant_id')
                                ->groupBy('product_variant_id')
                                ->havingRaw('SUM(quantity_on_hand) <= MIN(min_stock)')
                                ->havingRaw('SUM(quantity_on_hand) !=    0');
                        });
                    });
                }

                if ($filters['stock_status'] === 'normal') {
                    $variantQuery->whereHas('inventory', function ($inv) {
                        $inv->select('product_variant_id')
                            ->groupBy('product_variant_id')
                            ->havingRaw('SUM(quantity_on_hand) > MIN(min_stock)');
                    });
                }
            });
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function restore(string $id)
    {
        try {
            DB::beginTransaction();
            $product = $this->handleModel->find($id);

            if (!$product) {
                throw new Exception('Không tìm thấy sản phẩm.');
            }
            $product->update(['status_product' => 1]);
            DB::commit();

            return $product;
        } catch (\Throwable $th) {
            Log::error('Lỗi khi cập nhật trạng thái sản phẩm:', [
                'product_id' => $id,
                'error' => $th->getMessage()
            ]);
            return $this->returnError('Lỗi khi cập nhật trạng thái sản phẩm.');
        }
    }
    public function getProductVariantsById($productId)
    {
        return DB::table('products as p')
            ->join('product_variants as pv', 'pv.product_id', '=', 'p.id')
            ->join('product_variant_attributes as pva', 'pva.variant_id', '=', 'pv.id')
            ->join('attribute_values as av', 'av.id', '=', 'pva.attribute_value_id')
            ->join('attributes as a', 'a.id', '=', 'av.attribute_id')
            ->where('p.id', $productId)
            ->select([
                'p.name as product_name',
                'pv.id as variant_id',
                'a.name as attribute_name',
                'av.name as attribute_value',
            ])
            ->get();
    }

    public function getProductWithVariants($productId)
    {
        return $this->handleModel->with(['productVariants'])->findOrFail($productId);
    }
}
