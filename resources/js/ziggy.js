const Ziggy = {
    url: "http://127.0.0.1:8000",
    port: null,
    defaults: {},
    routes: {
        "admin.": { uri: "admin/dashboard", methods: ["GET", "HEAD"] },
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
            methods: ["PUT", "PATCH"],
            parameters: ["user"],
        },
        "admin.users.destroy": {
            uri: "admin/users/{user}",
            methods: ["DELETE"],
            parameters: ["user"],
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
