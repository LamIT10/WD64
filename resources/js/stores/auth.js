import { usePage } from "@inertiajs/vue3";
import { defineStore } from "pinia";
const page = usePage();
export const useAuthStore = defineStore("auth", {
    state: () => ({
        permissions: page.props.auth.permissions || [],
        roles: page.props.auth.roles  || [],
    }),
    actions: {
        hasPermission(permission) {
            return this.permissions.includes(permission);
        },
        hasRole(role) {
            return this.roles.includes(role);
        },
        clearPermissions() {
            this.permissions = [];
        },
        clearRoles() {
            this.roles = [];
        },
    },
});
