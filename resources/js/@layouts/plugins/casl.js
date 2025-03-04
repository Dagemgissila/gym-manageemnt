import common from '@/composables/common';

/**
 * Returns ability result if ACL is configured or else just return true
 * We should allow passing string | undefined to can because for admin ability we omit defining action & subject
 *
 * Useful if you don't know if ACL is configured or not
 * Used in @core files to handle absence of ACL without errors
 *
 * @param {string} action CASL Actions // https://casl.js.org/v4/en/guide/intro#basics
 * @param {string} subject CASL Subject // https://casl.js.org/v4/en/guide/intro#basics
 */
export const can = (action, subject) => {
  const vm = getCurrentInstance()
  if (!vm)
    return false
  const localCan = vm.proxy && '$can' in vm.proxy
    
  return localCan ? vm.proxy?.$can(action, subject) : true
}

/**
 * Check if user can view item based on it's ability
 * Based on item's action and subject & Hide group if all of it's children are hidden
 * @param {object} item navigation object item
 */
export const canViewNavMenuGroup = item => {
  // Get permissions from composable inside function
  const { permsArray } = common();

  // If the user has the 'admin' permission, always allow access
  if (permsArray.value.includes('admin')) {
      return true;
  }

  // If at least one child has no permission, allow access (show menu)
  if (item.children.some(child => !child.permission)) {
      return true;
  }

  // If at least one child has a permission that the user has, allow access
  if (item.children.some(child => child.permission && permsArray.value.includes(child.permission))) {
      return true;
  }

  // If no condition is met, hide the menu group
  return false;
};




export const canNavigate = to => {
  // Get permissions from composable inside function
  const { permsArray } = common();



  // If the user has the 'admin' permission, always allow access
  if (permsArray.value.includes('admin')) {
    return true;
  }

  // If the target route has specific permissions, check those
  const targetRoute = to.matched[to.matched.length - 1];
  console.log("kk",permsArray.value.includes(targetRoute.meta.permission))

  if (targetRoute?.meta?.permission) {
    return permsArray.value.includes(targetRoute.meta.permission);
  }

  // If no specific permissions are required, allow access
  return true;
};
