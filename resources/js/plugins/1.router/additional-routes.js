const MemberDetail = () => import("@/pages/members/details/index.vue");
const ForbiddenComponent = () => import("@/pages/forbidden.vue");
import membership from "@/pages/members/create/membership.vue";
import prospect from "@/pages/members/create/prospect.vue";
import trial from "@/pages/members/create/trial.vue";
// 👉 Redirects
export const redirects = [
  // ℹ️ We are redirecting to different pages based on role.
  // NOTE: Role is just for UI purposes. ACL is based on abilities.
  {
    path: "/",
    name: "index",
    redirect: (to) => {
      // TODO: Get type from backend
      const userData = localStorage.getItem("gms_user");
      // const userRole = userData.roles
      if (userData) return { name: "dashboards-crm" };

      return { name: "login", query: to.query };
    },
  },
  {
    path: "/pages/user-profile",
    name: "pages-user-profile",
    redirect: () => ({
      name: "pages-user-profile-tab",
      params: { tab: "profile" },
    }),
  },
  {
    path: "/pages/account-settings",
    name: "pages-account-settings",
    redirect: () => ({
      name: "pages-account-settings-tab",
      params: { tab: "account" },
    }),
  },
  {
    path: "/forbidden",
    name: "forbidden",
    component: ForbiddenComponent,
  },
];
export const routes = [
  {
    path: "/members/details/:id",
    name: "member-details",
    component: MemberDetail,
    meta:{
      requireAuth:true
    }
  },
  {
    path: "/members/create-prospect",
    name: "member-create-prospect",
    component: prospect,
    meta:{
      requireAuth:true
    }
  },
  {
    path: "/members/create-trial",
    name: "member-create-tria;",
    component: trial,
    meta:{
      requireAuth:true
    }
  },
  {
    path: "/members/create-membership",
    name: "member-create-membership",
    component: membership,
    meta:{
      requireAuth:true
    }
  },
];
