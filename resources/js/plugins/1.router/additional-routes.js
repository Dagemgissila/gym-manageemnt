// const emailRouteComponent = () => import('@/pages/apps/email/index.vue')
const ForbiddenComponent = () => import('@/pages/forbidden.vue')


// 👉 Redirects
export const redirects = [
  // ℹ️ We are redirecting to different pages based on role.
  // NOTE: Role is just for UI purposes. ACL is based on abilities.
  {
    path: '/',
    name: 'index',
    redirect: to => {
      // TODO: Get type from backend
      const userData = localStorage.getItem('gms_user');
      // const userRole = userData.roles
      if (userData)
        return { name: 'dashboards-crm' }

      
      return { name: 'login', query: to.query }
    },
  },
  {
    path: '/pages/user-profile',
    name: 'pages-user-profile',
    redirect: () => ({ name: 'pages-user-profile-tab', params: { tab: 'profile' } }),
  },
  {
    path: '/pages/account-settings',
    name: 'pages-account-settings',
    redirect: () => ({ name: 'pages-account-settings-tab', params: { tab: 'account' } }),
  },
  {
    path: '/forbidden',
    name: 'forbidden',
    component: ForbiddenComponent,
  },

]
export const routes = [
  // Email filter
  // {
  //   path: '/apps/email/filter/:filter',
  //   name: 'apps-email-filter',
  //   component: emailRouteComponent,
  //   meta: {
  //     navActiveLink: 'apps-email',
  //     layoutWrapperClasses: 'layout-content-height-fixed',
  //   },
  // },


  

  // Email label
  // {
  //   path: '/apps/email/label/:label',
  //   name: 'apps-email-label',
  //   component: emailRouteComponent,
  //   meta: {
  //     // contentClass: 'email-application',
  //     navActiveLink: 'apps-email',
  //     layoutWrapperClasses: 'layout-content-height-fixed',
  //   },
  // },
  // {
  //   path: '/dashboards/logistics',
  //   name: 'dashboards-logistics',
  //   component: () => import('@/pages/apps/logistics/dashboard.vue'),
  // },

]
