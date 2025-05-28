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
}
export { Ziggy };
