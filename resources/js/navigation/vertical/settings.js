export default [
    {
      title: 'Settings',
      icon: { icon: 'tabler-settings' }, // Settings icon
      children: [
        { 
          title: 'Public Rule', 
          to: 'settings-public-rule', 
          permission: 'public_rule_view',
          icon: { icon: 'tabler-settings-cog' } // Updated for system-wide settings
        },
        { 
          title: 'Form Validation', 
          to: 'settings-form-validation', 
          permission: 'users_view',
          icon: { icon: 'tabler-settings-cog' } // Updated for system-wide settings
        },

        { 
          title: 'Variable Fields', 
          icon: { icon: 'tabler-users' }, // Updated Membership icon
          to: 'settings-variable-field', 
          permission: 'field_validation_view' 
        },
        {
          title: 'Membership',
          icon: { icon: 'tabler-users' }, // Updated Membership icon
          children: [
            {
              title: 'Types',
              to: "settings-membership-type", // Replace with actual route
              icon: { icon: 'tabler-list' }, // List icon
              permission:"membership_type_view"
            },
            {
              title: 'Items',
              to: 'settings-membership', // Replace with actual route
              icon: { icon: 'tabler-package' }, // Package/item icon
              permission:"membership_item_view"
            },
          ],
        },


      ],
    },
  ];
