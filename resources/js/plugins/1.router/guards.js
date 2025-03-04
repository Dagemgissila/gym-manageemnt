import { canNavigate } from '@layouts/plugins/casl';
import { computed } from 'vue';
import { useStore } from 'vuex/dist/vuex.cjs.js';

export const setupGuards = (router) => {
  router.beforeEach((to) => {
    const store = useStore();
    const isLoggedIn = computed(() => store.getters["auth/isLoggedIn"]);


    // ✅ Allow public pages
    if (to.meta.public) return true;

    // ✅ Prevent authenticated users from visiting login/register pages
    if (to.meta.unauthenticatedOnly && isLoggedIn.value) {
      return { name: 'dashboards-crm' } // Redirect logged-in users away from auth pages
    }

    // ✅ Redirect unauthenticated users if `requireAuth` is `true`
    if (to.meta.requireAuth && !isLoggedIn.value) {
      return {
        name: 'login',
        query: { redirect: to.fullPath }, // Redirect back after login
      };
    }

    // ✅ Check permission-based navigation
    if (to.meta.permission && !canNavigate(to)) {
      return isLoggedIn.value
        ? { name: 'not-authorized' }
        : { name: 'login', query: { redirect: to.fullPath } };
    }

    return true;
  });
};
