const Ziggy = {
    url: "http://127.0.0.1:8000",
    port: null,
    defaults: {},
    routes: {
        "admin.": { uri: "admin/dashboard", methods: ["GET", "HEAD"] },
        "admin.role.index": { uri: "admin/role", methods: ["GET", "HEAD"] },
        "admin.role.create": {
            uri: "admin/role/create",
            methods: ["GET", "HEAD"],
        },
        "admin.role.store": { uri: "admin/role", methods: ["POST"] },
        "admin.role.show": {
            uri: "admin/role/{role}",
            methods: ["GET", "HEAD"],
            parameters: ["role"],
        },
        "admin.role.edit": {
            uri: "admin/role/{role}/edit",
            methods: ["GET", "HEAD"],
            parameters: ["role"],
        },
        "admin.role.update": {
            uri: "admin/role/{role}",
            methods: ["PUT", "PATCH"],
            parameters: ["role"],
        },
        "admin.role.destroy": {
            uri: "admin/role/{role}",
            methods: ["DELETE"],
            parameters: ["role"],
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
        "admin.permission.show": {
            uri: "admin/permission/{permission}",
            methods: ["GET", "HEAD"],
            parameters: ["permission"],
        },
        "admin.permission.edit": {
            uri: "admin/permission/{permission}/edit",
            methods: ["GET", "HEAD"],
            parameters: ["permission"],
        },
        "admin.permission.update": {
            uri: "admin/permission/{permission}",
            methods: ["PUT", "PATCH"],
            parameters: ["permission"],
        },
        "admin.permission.destroy": {
            uri: "admin/permission/{permission}",
            methods: ["DELETE"],
            parameters: ["permission"],
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
