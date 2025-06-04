import { usePage } from '@inertiajs/vue3';
import { defineStore } from 'pinia';
const page = usePage();
export const useAuthStore = defineStore('auth', {
  state: () => ({
    permissions: JSON.parse(localStorage.getItem('permissions')) || [],
    isPermissionsLoaded: !!localStorage.getItem('permissions'),
  }),
  actions: {
    setPermissions(permissions) {
      this.permissions = permissions;
      this.isPermissionsLoaded = true;
      localStorage.setItem('permissions', JSON.stringify(permissions));
    },
    hasPermission(permission) {
      
        if(this.permissions.length == 0 ){
            this.setPermissions(page.props.auth.permissions);
        }
      return this.permissions.includes(permission);
    },
    clearPermissions() {
      this.permissions = [];
      this.isPermissionsLoaded = false;
      localStorage.removeItem('permissions');
    },
  },
});