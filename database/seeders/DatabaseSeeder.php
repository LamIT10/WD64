<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call([
            Top10VariantSeeder::class,
            SettingSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            SupplierSeeder::class,
            UnitSeeder::class,
            ProductSeeder::class,
            AttributeSeeder::class,
            AttributeValueSeeder::class,
            ProductVariantSeeder::class,
            ProductVariantAttributeSeeder::class,
            ProductUnitConversionSeeder::class,
            RankSeeder::class,
            CustomerSeeder::class,
            WarehouseZoneSeeder::class,
            InventoryLocationSeeder::class,
            InventorySeeder::class,
            PurchaseOrderSeeder::class,
            PurchaseOrderItemSeeder::class,
            SaleOrderSeeder::class,
            SaleOrderItemSeeder::class,
            SupplierTransactionSeeder::class,
            CustomerTransactionSeeder::class,
            FinancialTransactionSeeder::class,
            WarehouseCostSeeder::class,
            ReceivingSeeder::class,
            ReceivingItemSeeder::class,
            ShippingSeeder::class,
            ShippingItemSeeder::class,
            InventoryAuditSeeder::class,
            InventoryAuditItemSeeder::class,
            ContactSeeder::class,
            DamagedExpiredProductSeeder::class,
            PermissionSeeder::class,
        ]);
    }
}