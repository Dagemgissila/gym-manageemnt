export default [
  {
    title: 'Users',
    icon: { icon: 'tabler-users' }, // Single user icon
    children: [
      { 
        title: 'Users', 
        to: 'apps-user-list', 
        permission: 'users_view' 
      },
      { 
        title: 'Role & Permissions', 
        to: 'apps-user-role-permissions', 
        permission: 'roles_view' 
      },
    ],
  },
  {
    title: 'Members',
    icon: { icon: 'tabler-users' }, // Multiple users icon
    children: [
      { 
        title: 'List', 
        to: 'members', 
        permission: 'users_view' 
      },

    ],
  },
]
