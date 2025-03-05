<script setup>
import axiosAdmin from "@/composables/axios/axiosAdmin";
import common from "@/composables/common";
import AddNewUserDrawer from "@/views/apps/user/list/AddNewUserDrawer.vue";
import EditUserDrawer from "@/views/apps/user/list/EditUserDrawer.vue";
import { onMounted, ref } from "vue";

// 👉 Search and Filters
const searchQuery = ref("");
const selectedRole = ref();
const selectedStatus = ref();

const { permsArray } = common();

// 👉 Data Table Options
const itemsPerPage = ref(10);
const page = ref(1);
const sortBy = ref("id");
const orderBy = ref("desc");
const selectedRows = ref([]);
const selected_user=ref({});
// 👉 Add User Dialog
const isAddNewUserDrawerVisible = ref(false);
const isEditUserDrawerVisible = ref(false);

// 👉 Users Data
const users = ref([]);
const totalUsers = ref(0);
const roles = ref([]);

// 👉 Headers
const headers = [
  { title: "User", key: "user" },
  { title: "Role", key: "role" },
  { title: "Status", key: "status" },
  { title: "Actions", key: "actions", sortable: false },
];

// 👉 Fetch Users
const fetchUsers = async () => {
  try {
    const { data, meta } = await axiosAdmin.get("/users", {
      params: {
        q: searchQuery.value,
        status: selectedStatus.value,
        role: selectedRole.value,
        page: page.value,
        itemsPerPage: itemsPerPage.value,
        sortBy: sortBy.value,
        orderBy: orderBy.value,
      },
    });

    users.value = data;
    totalUsers.value = meta.total;
  } catch (error) {
    console.error("Error fetching users:", error);
  }
};

const fetchRoles = async () => {
  try {
    const response = await axiosAdmin.get("/roles");

    roles.value = response.data.map((role) => ({
      title: role.display_name || role.name, // Use display_name if available, otherwise fallback to name
      value: role.name,
    }));
  } catch (error) {
    console.error("Error fetching roles:", error);
  }
};


const fetchUserById = async (id) => {
  try {
    const response = await axiosAdmin.get(`/users/${id}`);
    selected_user.value = response.data;
    isEditUserDrawerVisible.value = true;
    console.log(isEditUserDrawerVisible.value);
  } catch (error) {
    console.error(error); // Always log the error for debugging
  }
}


// 👉 Update sorting options
const updateOptions = (options) => {
  if (options.sortBy.length > 0) {
    sortBy.value = options.sortBy[0].key;
    orderBy.value = options.sortBy[0].order;
  } else {
    sortBy.value = "id";
    orderBy.value = "desc";
  }
};

// 👉 Watch for changes that require data refresh
watch([page, itemsPerPage, sortBy, orderBy], fetchUsers);
watch([searchQuery, selectedRole, selectedStatus], () => {
  // Reset to first page when filters change
  page.value = 1;
  fetchUsers();
});

// 👉 Initial fetch
onMounted(async () => {
  await fetchUsers();
  await fetchRoles();
});

const status = [
  { title: "Active", value: "active" },
  { title: "Suspended", value: "suspended" },
  { title: "Inactive", value: "inactive" },
];

const resolveUserRoleVariant = (role) => {
  const roleLowerCase = role.toLowerCase();
  if (roleLowerCase === "admin")
    return {
      color: "primary",
      icon: "tabler-crown",
    };

  return {
    color: "primary",
    icon: "tabler-user",
  };
};

const resolveUserStatusVariant = (stat) => {
  const stats = {
    active: "success",
    suspended: "warning",
    inactive: "secondary",
  };
  return stats[stat.toLowerCase()] || "primary";
};


</script>

<template>
  <section>
    <VCard class="mb-6">
      <VCardItem class="pb-4">
        <VCardTitle>Filters</VCardTitle>
      </VCardItem>

      <VCardText>
        <VRow>
          <!-- 👉 Select Role -->
          <VCol cols="12" sm="4">
            <AppSelect
              v-model="selectedRole"
              placeholder="Select Role"
              :items="roles"
              clearable
              clear-icon="tabler-x"
            />
          </VCol>
          <!-- 👉 Select Plan -->
          <VCol cols="12" sm="4">
            <!-- 👉 Search  -->
            <div>
              <AppTextField
                v-model="searchQuery"
                placeholder="Search User"
                clearable
                clear-icon="tabler-x"
              />
            </div>
          </VCol>
          <!-- 👉 Select Status -->
          <VCol cols="12" sm="4">
            <AppSelect
              v-model="selectedStatus"
              placeholder="Select Status"
              :items="status"
              clearable
              clear-icon="tabler-x"
            />
          </VCol>
        </VRow>
      </VCardText>

      <VDivider />

      <VCardText class="d-flex flex-wrap gap-4">
        <div class="me-3 d-flex gap-3">
          <AppSelect
            :model-value="itemsPerPage"
            :items="[
              { value: 10, title: '10' },
              { value: 25, title: '25' },
              { value: 50, title: '50' },
              { value: 100, title: '100' },
              { value: -1, title: 'All' },
            ]"
            style="inline-size: 6.25rem"
            @update:model-value="itemsPerPage = parseInt($event, 10)"
          />
        </div>
        <VSpacer />

        <div class="app-user-search-filter d-flex align-center flex-wrap gap-4">
          <!-- 👉 Add user button -->
          <VBtn
            v-if="
              permsArray.includes('users_create') ||
              permsArray.includes('admin')
            "
            prepend-icon="tabler-plus"
            @click="isAddNewUserDrawerVisible = true"
          >
            Add New User
          </VBtn>
        </div>
      </VCardText>

      <VDivider />

      <!-- SECTION datatable -->
      <VDataTableServer
        v-model:items-per-page="itemsPerPage"
        v-model:model-value="selectedRows"
        v-model:page="page"
        :items="users"
        item-value="id"
        :items-length="totalUsers"
        :headers="headers"
        class="text-no-wrap"
        show-select
        @update:options="updateOptions"
      >
      <template #item.user="{ item }">
          <div class="d-flex align-center gap-x-3">
            <VAvatar
              size="36"
              :variant="!item.avatar ? 'tonal' : undefined"
            >
              <VImg
                v-if="item.profile_picture"
                :src="item.profile_picture"
              />
              <span v-else>{{ avatarText(item.first_name + ' ' + item.last_name) }}</span>
            </VAvatar>
            <div class="d-flex flex-column">
              <RouterLink
                to=""
                class="text-link font-weight-medium d-inline-block"
                style="line-height: 1.375rem;"
              >
                {{ item.first_name }}
              </RouterLink>
              <div class="text-body-2">
                {{ item.email }}
              </div>
            </div>
          </div>
        </template>

        <!-- 👉 Role -->
        <template #item.role="{ item }">
          <div class="d-flex align-center gap-x-2">
            <VIcon
              :size="22"
              :icon="resolveUserRoleVariant(item.role).icon"
              :color="resolveUserRoleVariant(item.role).color"
            />

            <div class="text-capitalize text-high-emphasis text-body-1">
              {{ item.role }}
            </div>
          </div>
        </template>

        <!-- Status -->
        <template #item.status="{ item }">
          <VChip
            :color="resolveUserStatusVariant(item.status)"
            size="small"
            label
            class="text-capitalize"
          >
            {{ item.status }}
          </VChip>
        </template>

        <!-- Actions -->
        <template #item.actions="{ item }">
          <VBtn icon variant="text" color="medium-emphasis">
            <VIcon icon="tabler-dots-vertical" />
            <VMenu activator="parent">
              <VList>
                <VListItem
                  :to="{ name: 'apps-user-view-id', params: { id: item.id } }"
                >
                  <template
                    #prepend
                    v-if="
                      permsArray.includes('users_view') ||
                      permsArray.includes('admin')
                    "
                  >
                    <VIcon icon="tabler-eye" />
                  </template>

                  <VListItemTitle>View</VListItemTitle>
                </VListItem>

                <VListItem
                  link
                  v-if="
                    permsArray.includes('users_edit') ||
                    permsArray.includes('admin')
                  "
                  @click="fetchUserById(item.id)"
                >
                  <template #prepend>
                    <VIcon icon="tabler-pencil" />
                  </template>
            
            <VListItemTitle >Edit</VListItemTitle>
                </VListItem>

                <VListItem
                  v-if="
                    permsArray.includes('users_delete') ||
                    permsArray.includes('admin')
                  "
                  @click="deleteUser(item.id)"
                >
                  <template #prepend>
                    <VIcon icon="tabler-trash" />
                  </template>
                  <VListItemTitle>Delete</VListItemTitle>
                </VListItem>
              </VList>
            </VMenu>
          </VBtn>
        </template>

        <!-- pagination -->
        <template #bottom>
          <TablePagination
            v-model:page="page"
            :items-per-page="itemsPerPage"
            :total-items="totalUsers"
          />
        </template>
      </VDataTableServer>
      <!-- SECTION -->
    </VCard>
    <!-- Add User Drawer -->
    <AddNewUserDrawer
      v-model:is-drawer-open="isAddNewUserDrawerVisible"
      @user-data="fetchUsers"
    />

    <EditUserDrawer
      v-model:is-drawer-open="isEditUserDrawerVisible"
      :selectedUser="selected_user"
      @user-data="fetchUsers"
    >
    </EditUserDrawer>
  </section>
</template>
