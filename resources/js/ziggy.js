<<<<<<< HEAD
const Ziggy = { "url": "http:\/\/127.0.0.1:8000", "port": null, "defaults": {}, "routes": { "admin.": { "uri": "admin\/dashboard", "methods": ["GET", "HEAD"] }, "admin.categories.index": { "uri": "admin\/categories", "methods": ["GET", "HEAD"] }, "admin.categories.create": { "uri": "admin\/categories\/create", "methods": ["GET", "HEAD"] }, "admin.categories.store": { "uri": "admin\/categories", "methods": ["POST"] }, "admin.categories.show": { "uri": "admin\/categories\/{category}", "methods": ["GET", "HEAD"], "parameters": ["category"] }, "admin.categories.edit": { "uri": "admin\/categories\/{category}\/edit", "methods": ["GET", "HEAD"], "parameters": ["category"] }, "admin.categories.update": { "uri": "admin\/categories\/{category}", "methods": ["PUT", "PATCH"], "parameters": ["category"] }, "admin.categories.destroy": { "uri": "admin\/categories\/{category}", "methods": ["DELETE"], "parameters": ["category"] }, "admin.products.index": { "uri": "admin\/products", "methods": ["GET", "HEAD"] }, "admin.products.create": { "uri": "admin\/products\/create", "methods": ["GET", "HEAD"] }, "admin.products.store": { "uri": "admin\/products", "methods": ["POST"] }, "admin.products.show": { "uri": "admin\/products\/{product}", "methods": ["GET", "HEAD"], "parameters": ["product"] }, "admin.products.edit": { "uri": "admin\/products\/{product}\/edit", "methods": ["GET", "HEAD"], "parameters": ["product"] }, "admin.products.update": { "uri": "admin\/products\/{product}", "methods": ["PUT", "PATCH"], "parameters": ["product"] }, "admin.products.destroy": { "uri": "admin\/products\/{product}", "methods": ["DELETE"], "parameters": ["product"] }, "storage.local": { "uri": "storage\/{path}", "methods": ["GET", "HEAD"], "wheres": { "path": ".*" }, "parameters": ["path"] } } };
if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
  Object.assign(Ziggy.routes, window.Ziggy.routes);
=======
const Ziggy = {
    url: "http://127.0.0.1:8000",
    port: null,
    defaults: {},
    routes: {
        "admin.": { uri: "admin/dashboard", methods: ["GET", "HEAD"] },
        "admin.suppliers.index": {
            uri: "admin/suppliers",
            methods: ["GET", "HEAD"],
        },
        "admin.suppliers.create": {
            uri: "admin/suppliers/create",
            methods: ["GET", "HEAD"],
        },
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
>>>>>>> fece6d1f3b00cb86a1b2ff82df705779601203c3
}
export { Ziggy };
