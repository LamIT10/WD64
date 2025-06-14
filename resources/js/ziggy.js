const Ziggy = {
    url: "http://127.0.0.1:8000",
    port: null,
    defaults: {},
    routes: {
        "sanctum.csrf-cookie": {
            uri: "sanctum/csrf-cookie",
            methods: ["GET", "HEAD"],
        },
        "admin.": { uri: "admin/dashboard", methods: ["GET", "HEAD"] },
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
            methods: ["PUT", "PATCH"],
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
            methods: ["PUT", "PATCH"],
            parameters: ["product"],
        },
        "admin.products.destroy": {
            uri: "admin/products/{product}",
            methods: ["DELETE"],
            parameters: ["product"],
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
        "admin.role.admin.customers.bulk-delete": {
            uri: "admin/role/admin/customers/bulk-delete",
            methods: ["POST"],
        },
        "admin.role.admin.customers.import": {
            uri: "admin/role/admin/customers/import",
            methods: ["GET", "HEAD"],
        },
        "admin.role.admin.customers.export": {
            uri: "admin/role/admin/customers/export",
            methods: ["GET", "HEAD"],
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
        "admin.customer-transaction.index": {
            uri: "admin/customer-transaction",
            methods: ["GET", "HEAD"],
        },
        "admin.customer-transaction.create": {
            uri: "admin/customer-transaction/create",
            methods: ["GET", "HEAD"],
        },
        "admin.customer-transaction.edit": {
            uri: "admin/customer-transaction/{id}/edit",
            methods: ["GET", "HEAD"],
            parameters: ["id"],
        },
        "admin.customer-transaction.store": {
            uri: "admin/customer-transaction/store",
            methods: ["POST"],
        },
        "admin.customer-transaction.update": {
            uri: "admin/customer-transaction/{id}/update",
            methods: ["PATCH"],
            parameters: ["id"],
        },
        "admin.customer-transaction.destroy": {
            uri: "admin/customer-transaction/{id}",
            methods: ["DELETE"],
            parameters: ["id"],
        },
        "admin.supplier-transaction.index": {
            uri: "admin/supplier-transaction",
            methods: ["GET", "HEAD"],
        },
        "admin.supplier-transaction.create": {
            uri: "admin/supplier-transaction/create",
            methods: ["GET", "HEAD"],
        },
        "admin.supplier-transaction.edit": {
            uri: "admin/supplier-transaction/{id}/edit",
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
        "admin.supplier-transaction.destroy": {
            uri: "admin/supplier-transaction/{id}",
            methods: ["DELETE"],
            parameters: ["id"],
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
