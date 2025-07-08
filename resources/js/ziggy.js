const Ziggy = {
    url: "http://127.0.0.1:8000/",
    port: null,
    defaults: {},
    routes: {
        "sanctum.csrf-cookie": {
            uri: "sanctum/csrf-cookie",
            methods: ["GET", "HEAD"],
        },
        "admin.dashboard": { uri: "admin/dashboard", methods: ["GET", "HEAD"] },
        "admin.inventory-audit.index": {
            uri: "admin/inventory-audit",
            methods: ["GET", "HEAD"],
        },
        "admin.inventory-audit.create": {
            uri: "admin/inventory-audit/create",
            methods: ["GET", "HEAD"],
        },
        "admin.inventory-audit.store": {
            uri: "admin/inventory-audit",
            methods: ["POST"],
        },
        "admin.inventory-audit.show": {
            uri: "admin/inventory-audit/{inventory_audit}",
            methods: ["GET", "HEAD"],
            parameters: ["inventory_audit"],
        },
        "admin.inventory-audit.edit": {
            uri: "admin/inventory-audit/{inventory_audit}/edit",
            methods: ["GET", "HEAD"],
            parameters: ["inventory_audit"],
        },
        "admin.inventory-audit.update": {
            uri: "admin/inventory-audit/{inventory_audit}",
            methods: ["PUT", "PATCH"],
            parameters: ["inventory_audit"],
        },
        "admin.inventory-audit.destroy": {
            uri: "admin/inventory-audit/{inventory_audit}",
            methods: ["DELETE"],
            parameters: ["inventory_audit"],
        },
        "admin.inventory.index": {
            uri: "admin/inventory",
            methods: ["GET", "HEAD"],
        },
        "admin.inventory.create": {
            uri: "admin/inventory/create",
            methods: ["GET", "HEAD"],
        },
        "admin.inventory.store": { uri: "admin/inventory", methods: ["POST"] },
        "admin.inventory.show": {
            uri: "admin/inventory/{id}",
            methods: ["GET", "HEAD"],
            parameters: ["id"],
        },
        "admin.inventory.edit": {
            uri: "admin/inventory/{id}/edit",
            methods: ["GET", "HEAD"],
            parameters: ["id"],
        },
        "admin.inventory.update": {
            uri: "admin/inventory/{id}",
            methods: ["PUT"],
            parameters: ["id"],
        },
        "admin.inventory.destroy": {
            uri: "admin/inventory/{id}",
            methods: ["DELETE"],
            parameters: ["id"],
        },
        "admin.categories.index": {
            uri: "admin/categories",
            methods: ["GET", "HEAD"],
        },
        "admin.categories.create": {
            uri: "admin/categories/create",
            methods: ["GET", "HEAD"],
        },
        "admin.categories.store": {
            uri: "admin/categories",
            methods: ["POST"],
        },
        "admin.categories.show": {
            uri: "admin/categories/{category}",
            methods: ["GET", "HEAD"],
            parameters: ["category"],
        },
        "admin.categories.edit": {
            uri: "admin/categories/{category}/edit",
            methods: ["GET", "HEAD"],
            parameters: ["category"],
        },
        "admin.categories.update": {
            uri: "admin/categories/{category}",
            methods: ["PUT"],
            parameters: ["category"],
        },
        "admin.categories.destroy": {
            uri: "admin/categories/{category}",
            methods: ["DELETE"],
            parameters: ["category"],
        },
        "admin.products.index": {
            uri: "admin/products",
            methods: ["GET", "HEAD"],
        },
        "admin.products.create": {
            uri: "admin/products/create",
            methods: ["GET", "HEAD"],
        },
        "admin.products.store": { uri: "admin/products", methods: ["POST"] },
        "admin.products.show": {
            uri: "admin/products/{product}",
            methods: ["GET", "HEAD"],
            parameters: ["product"],
        },
        "admin.products.edit": {
            uri: "admin/products/{product}/edit",
            methods: ["GET", "HEAD"],
            parameters: ["product"],
        },
        "admin.products.update": {
            uri: "admin/products/{product}",
            methods: ["PUT"],
            parameters: ["product"],
        },
        "admin.products.destroy": {
            uri: "admin/products/{product}",
            methods: ["DELETE"],
            parameters: ["product"],
        },
        "admin.products.search": {
            uri: "admin/products/search",
            methods: ["GET", "HEAD"],
        },
        "admin.products.variants": {
            uri: "admin/products/{productId}/variants/{supplierId}",
            methods: ["GET", "HEAD"],
            parameters: ["productId", "supplierId"],
        },
        "admin.attributes.index": {
            uri: "admin/attributes",
            methods: ["GET", "HEAD"],
        },
        "admin.attributes.store": {
            uri: "admin/attributes",
            methods: ["POST"],
        },
        "admin.attributes.destroy": {
            uri: "admin/attributes/{id}",
            methods: ["DELETE"],
            parameters: ["id"],
        },
        "admin.attribute-values.store": {
            uri: "admin/attribute-values",
            methods: ["POST"],
        },
        "admin.attribute-values.destroy": {
            uri: "admin/attribute-values/{id}",
            methods: ["DELETE"],
            parameters: ["id"],
        },
        "admin.units.index": { uri: "admin/units", methods: ["GET", "HEAD"] },
        "admin.units.store": { uri: "admin/units", methods: ["POST"] },
        "admin.units.destroy": {
            uri: "admin/units/{id}",
            methods: ["DELETE"],
            parameters: ["id"],
        },
        "admin.customers.index": {
            uri: "admin/customers",
            methods: ["GET", "HEAD"],
        },
        "admin.customers.create": {
            uri: "admin/customers/create",
            methods: ["GET", "HEAD"],
        },
        "admin.customers.store": { uri: "admin/customers", methods: ["POST"] },
        "admin.customers.show": {
            uri: "admin/customers/{customer}",
            methods: ["GET", "HEAD"],
            parameters: ["customer"],
            bindings: { customer: "id" },
        },
        "admin.customers.edit": {
            uri: "admin/customers/{customer}/edit",
            methods: ["GET", "HEAD"],
            parameters: ["customer"],
            bindings: { customer: "id" },
        },
        "admin.customers.update": {
            uri: "admin/customers/{customer}",
            methods: ["PATCH"],
            parameters: ["customer"],
            bindings: { customer: "id" },
        },
        "admin.customers.destroy": {
            uri: "admin/customers/{customer}",
            methods: ["DELETE"],
            parameters: ["customer"],
            bindings: { customer: "id" },
        },
        "admin.customers.customers.bulk-delete": {
            uri: "admin/customers/customers/bulk-delete",
            methods: ["POST"],
        },
        "admin.customers.customers.import": {
            uri: "admin/customers/customers/import",
            methods: ["GET", "HEAD"],
        },
        "admin.customers.customers.export": {
            uri: "admin/customers/customers/export",
            methods: ["GET", "HEAD"],
        },
        "admin.ranks.index": { uri: "admin/ranks", methods: ["GET", "HEAD"] },
        "admin.ranks.create": {
            uri: "admin/ranks/create",
            methods: ["GET", "HEAD"],
        },
        "admin.ranks.store": { uri: "admin/ranks", methods: ["POST"] },
        "admin.ranks.show": {
            uri: "admin/ranks/{rank}",
            methods: ["GET", "HEAD"],
            parameters: ["rank"],
        },
        "admin.ranks.edit": {
            uri: "admin/ranks/{rank}/edit",
            methods: ["GET", "HEAD"],
            parameters: ["rank"],
            bindings: { rank: "id" },
        },
        "admin.ranks.update": {
            uri: "admin/ranks/{rank}",
            methods: ["PATCH"],
            parameters: ["rank"],
            bindings: { rank: "id" },
        },
        "admin.ranks.destroy": {
            uri: "admin/ranks/{rank}",
            methods: ["DELETE"],
            parameters: ["rank"],
            bindings: { rank: "id" },
        },
        "admin.permission.index": {
            uri: "admin/permission",
            methods: ["GET", "HEAD"],
        },
        "admin.permission.create": {
            uri: "admin/permission/create",
            methods: ["GET", "HEAD"],
        },
        "admin.permission.store": {
            uri: "admin/permission",
            methods: ["POST"],
        },
        "admin.permission.edit": {
            uri: "admin/permission/{id}/edit",
            methods: ["GET", "HEAD"],
            parameters: ["id"],
        },
        "admin.permission.update": {
            uri: "admin/permission/{id}",
            methods: ["PATCH"],
            parameters: ["id"],
        },
        "admin.permission.destroy": {
            uri: "admin/permission/{id}",
            methods: ["DELETE"],
            parameters: ["id"],
        },
        "admin.permission.show": {
            uri: "admin/permission/{id}",
            methods: ["GET", "HEAD"],
            parameters: ["id"],
        },
        "admin.role.index": { uri: "admin/role", methods: ["GET", "HEAD"] },
        "admin.role.create": {
            uri: "admin/role/create",
            methods: ["GET", "HEAD"],
        },
        "admin.role.store": { uri: "admin/role", methods: ["POST"] },
        "admin.role.edit": {
            uri: "admin/role/{id}/edit",
            methods: ["GET", "HEAD"],
            parameters: ["id"],
        },
        "admin.role.update": {
            uri: "admin/role/{id}",
            methods: ["PATCH"],
            parameters: ["id"],
        },
        "admin.role.destroy": {
            uri: "admin/role/{id}",
            methods: ["DELETE"],
            parameters: ["id"],
        },
        "admin.role.show": {
            uri: "admin/role/{id}",
            methods: ["GET", "HEAD"],
            parameters: ["id"],
        },
        "admin.suppliers.index": {
            uri: "admin/suppliers",
            methods: ["GET", "HEAD"],
        },
        "admin.suppliers.create": {
            uri: "admin/suppliers/create",
            methods: ["GET", "HEAD"],
        },
        "admin.suppliers.edit": {
            uri: "admin/suppliers/{id}/edit",
            methods: ["GET", "HEAD"],
            parameters: ["id"],
        },
        "admin.suppliers.store": {
            uri: "admin/suppliers/store",
            methods: ["POST"],
        },
        "admin.suppliers.update": {
            uri: "admin/suppliers/{id}/update",
            methods: ["PATCH"],
            parameters: ["id"],
        },
        "admin.suppliers.destroy": {
            uri: "admin/suppliers/{id}",
            methods: ["DELETE"],
            parameters: ["id"],
        },
        "admin.suppliers.products": {
            uri: "admin/suppliers/{id}/products",
            methods: ["GET", "HEAD"],
            parameters: ["id"],
        },
        "admin.suppliers.products.store": {
            uri: "admin/suppliers/{supplierId}/products",
            methods: ["POST"],
            parameters: ["supplierId"],
        },
        "admin.suppliers.variants": {
            uri: "admin/suppliers/{supplierId}/products/{productId}/variants",
            methods: ["GET", "HEAD"],
            parameters: ["supplierId", "productId"],
        },
        "admin.customer-transaction.index": {
            uri: "admin/customer-transaction",
            methods: ["GET", "HEAD"],
        },
        "admin.customer-transaction.add": {
            uri: "admin/customer-transaction/{order}/add",
            methods: ["POST"],
            parameters: ["order"],
        },
        "admin.customer-transaction.updateDueDate": {
            uri: "admin/customer-transaction/{order}/update-due-date",
            methods: ["POST"],
            parameters: ["order"],
        },
        "admin.customer-transaction.show": {
            uri: "admin/customer-transaction/{order}/show",
            methods: ["GET", "HEAD"],
            parameters: ["order"],
        },
        "admin.supplier-transaction.index": {
            uri: "admin/supplier-transaction",
            methods: ["GET", "HEAD"],
        },
        "admin.supplier-transaction.show": {
            uri: "admin/supplier-transaction/{id}/show",
            methods: ["GET", "HEAD"],
            parameters: ["id"],
        },
        "admin.supplier-transaction.store": {
            uri: "admin/supplier-transaction/store",
            methods: ["POST"],
        },
        "admin.supplier-transaction.update": {
            uri: "admin/supplier-transaction/{id}/update",
            methods: ["PATCH"],
            parameters: ["id"],
        },
        "admin.supplier-transaction.updatePayment": {
            uri: "admin/supplier-transaction/{id}/update-payment",
            methods: ["PATCH"],
            parameters: ["id"],
        },
        "admin.purchases.index": {
            uri: "admin/purchases",
            methods: ["GET", "HEAD"],
        },
        "admin.purchases.create": {
            uri: "admin/purchases/create",
            methods: ["GET", "HEAD"],
        },
        "admin.purchases.approve": {
            uri: "admin/purchases/{id}/approve",
            methods: ["POST"],
            parameters: ["id"],
        },
        "admin.purchases.getVariants": {
            uri: "admin/purchases/{id}/get-variants",
            methods: ["GET", "HEAD"],
            parameters: ["id"],
        },
        "admin.purchases.getSupplierAndUnit": {
            uri: "admin/purchases/{id}/get-supplier-and-unit",
            methods: ["GET", "HEAD"],
            parameters: ["id"],
        },
        "admin.purchases.store": {
            uri: "admin/purchases/store",
            methods: ["POST"],
        },
        "admin.receiving.index": {
            uri: "admin/receiving",
            methods: ["GET", "HEAD"],
        },
        "admin.receiving.create": {
            uri: "admin/receiving/{id}/create",
            methods: ["GET", "HEAD"],
            parameters: ["id"],
        },
        "admin.receiving.approve": {
            uri: "admin/receiving/{id}/approve",
            methods: ["POST"],
            parameters: ["id"],
        },
        "admin.receiving.getVariants": {
            uri: "admin/receiving/{id}/get-variants",
            methods: ["GET", "HEAD"],
            parameters: ["id"],
        },
        "admin.receiving.getSupplierAndUnit": {
            uri: "admin/receiving/{id}/get-supplier-and-unit",
            methods: ["GET", "HEAD"],
            parameters: ["id"],
        },
        "admin.receiving.store": {
            uri: "admin/receiving/store",
            methods: ["POST"],
        },
        "admin.users.index": { uri: "admin/users", methods: ["GET", "HEAD"] },
        "admin.users.create": {
            uri: "admin/users/create",
            methods: ["GET", "HEAD"],
        },
        "admin.users.store": { uri: "admin/users", methods: ["POST"] },
        "admin.users.show": {
            uri: "admin/users/{user}",
            methods: ["GET", "HEAD"],
            parameters: ["user"],
        },
        "admin.users.edit": {
            uri: "admin/users/{user}/edit",
            methods: ["GET", "HEAD"],
            parameters: ["user"],
        },
        "admin.users.update": {
            uri: "admin/users/{user}",
            methods: ["PUT"],
            parameters: ["user"],
        },
        "admin.users.destroy": {
            uri: "admin/users/{user}",
            methods: ["DELETE"],
            parameters: ["user"],
        },
        "admin.users.bulk-update-status": {
            uri: "admin/users/update-status",
            methods: ["POST"],
        },
        "admin.users.bulk-delete": {
            uri: "admin/users/bulk-delete",
            methods: ["POST"],
        },
        "admin.sale-orders.index": {
            uri: "admin/sale-orders",
            methods: ["GET", "HEAD"],
        },
        "admin.sale-orders.create": {
            uri: "admin/sale-orders/create",
            methods: ["GET", "HEAD"],
        },
        "admin.sale-orders.products.search": {
            uri: "admin/sale-orders/search/products",
            methods: ["GET", "HEAD"],
        },
        "admin.sale-orders.variants.all": {
            uri: "admin/sale-orders/variants/{productId}",
            methods: ["GET", "HEAD"],
            parameters: ["productId"],
        },
        "admin.sale-orders.unit.all": {
            uri: "admin/sale-orders/unit-conversions/{productId}",
            methods: ["GET", "HEAD"],
            parameters: ["productId"],
        },
        "admin.sale-orders.customer.search": {
            uri: "admin/sale-orders/search/customers",
            methods: ["GET", "HEAD"],
        },
        "admin.sale-orders.inventory": {
            uri: "admin/sale-orders/inventory/{productVariantId}",
            methods: ["GET", "HEAD"],
            parameters: ["productVariantId"],
        },
        "admin.sale-orders.reject": {
            uri: "admin/sale-orders/{id}/reject",
            methods: ["POST"],
            parameters: ["id"],
        },
        "admin.sale-orders.approve": {
            uri: "admin/sale-orders/{id}/approve",
            methods: ["POST"],
            parameters: ["id"],
        },
        "admin.sale-orders.store": {
            uri: "admin/sale-orders/store",
            methods: ["POST"],
        },
        "admin.sale-orders.export": {
            uri: "admin/sale-orders/export",
            methods: ["GET", "HEAD"],
        },
        "admin.sale-orders.complete": {
            uri: "admin/sale-orders/{id}/complete",
            methods: ["POST"],
            parameters: ["id"],
        },
        dashboard: { uri: "dashboard", methods: ["GET", "HEAD"] },
        login: { uri: "login", methods: ["GET", "HEAD"] },
        logout: { uri: "logout", methods: ["POST"] },
        "password.request": {
            uri: "forgot-password",
            methods: ["GET", "HEAD"],
        },
        "password.email": { uri: "forgot-password", methods: ["POST"] },
        "password.reset": {
            uri: "reset-password/{token}",
            methods: ["GET", "HEAD"],
            parameters: ["token"],
        },
        "password.update": { uri: "reset-password", methods: ["POST"] },
        "google.login": { uri: "auth/google", methods: ["GET", "HEAD"] },
        profile: { uri: "profile", methods: ["GET", "HEAD"] },
        "storage.local": {
            uri: "storage/{path}",
            methods: ["GET", "HEAD"],
            wheres: { path: ".*" },
            parameters: ["path"],
        },
    },
};
if (typeof window !== "undefined" && typeof window.Ziggy !== "undefined") {
    Object.assign(Ziggy.routes, window.Ziggy.routes);
}
export { Ziggy };
