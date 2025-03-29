<script setup>
import axiosAdmin from "@/composables/axios/axiosAdmin";
import common from "@/composables/common";
import AddNewRoleDrawer from "@/views/apps/user/role-permission/AddNewRoleDrawer.vue";
import EditRoleDrawer from "@/views/apps/user/role-permission/EditRoleDrawer.vue";
import { onMounted, ref } from "vue";
import { toast } from "vue3-toastify";
// 👉 Data Table Options
const itemsPerPage = ref(10);
const page = ref(1);
const sortBy = ref("id");
const orderBy = ref("desc");
const selectedRows = ref([]);

const { permsArray } = common();

// 👉 Users Data
const roles_list = ref([]);
const total_roles = ref(0);

// 👉 Headers
const headers = [
  { title: "Role", key: "role" },
  { title: "Description", key: "description" },
  { title: "Actions", key: "actions", sortable: false },
];

// 👉 Fetch Role
const fetchRoles = async () => {
  try {
    const response = await axiosAdmin.get("/roles", {
      params: {
        page: page.value,
        itemsPerPage: itemsPerPage.value,
      },
    });

    roles_list.value = response.data;
    total_roles.value = response.meta.total;
  } catch (error) {
    console.error("Error fetching users:", error);
  }
};

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
watch([page, itemsPerPage, sortBy, orderBy], fetchRoles);

// 👉 Initial fetch
onMounted(fetchRoles);

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

// 👉 Add User Dialog
const isAddNewRoleDrawerVisible = ref(false);
const editRoleDrawerVisible = ref(false);
const selectedRole = ref({});

const addNewRole = async (roleData) => {
  const formattedRoleData = {
    role_name: roleData.role_name,
    is_trainer:roleData.is_trainer,
    description: roleData.description,
    permissions: [...roleData.permissions], // Convert Proxy(Array) to plain array
  };

  axiosAdmin
    .post("/add-role-permissions", formattedRoleData)
    .then(function (response) {
      toast("Role created successfully", {
        theme: "colored",
        type: "success",
        position: "top-right",
        dangerouslyHTMLString: true,
      });
      fetchRoles();
    })
    .catch(function (error) {
      console.log(error);
    });

  // Refetch User
};

const fetchRoleById = async (roleId) => {
  try {
    const { data } = await axiosAdmin.get(`/roles/${roleId}`); // Fetch role by ID
    console.log(data)
    selectedRole.value = {
      id: data.id,
      role_name: data.role_name,
      name: data.name,
      is_trainer:data.is_trainer,
      description: data.description,
      permissions: data.permissions, // Extract permission IDs
    };

    editRoleDrawerVisible.value = true; // Open the drawer
  } catch (error) {
    console.error("Error fetching role:", error);
  }
};

const updateRole = async (roleData) => {
  const formattedRoleData = {
    id: roleData.id,
    role_name: roleData.role_name,
    is_trainer:roleData.is_trainer,
    description: roleData.description,
    permissions: [...roleData.permissions], // Convert Proxy(Array) to plain array
  };

  axiosAdmin
    .patch("/edit-role-permissions", formattedRoleData)
    .then(function (response) {
      toast("Role updated successfully", {
        theme: "colored",
        type: "success",
        position: "top-right",
        dangerouslyHTMLString: true,
      });
      // Refetch User
      fetchRoles();
    })
    .catch(function (error) {
      console.log(error);
    });

  // Refetch User
  fetchRoles();
};
</script>

<template>
  <section>
    <VCard class="mb-6">
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
              permsArray.includes('roles_create') ||
              permsArray.includes('admin')
            "
            prepend-icon="tabler-plus"
            @click="isAddNewRoleDrawerVisible = true"
          >
            Add New Role
          </VBtn>
        </div>
      </VCardText>

      <VDivider />

      <!-- SECTION datatable -->
      <VDataTableServer
        v-model:items-per-page="itemsPerPage"
        v-model:model-value="selectedRows"
        v-model:page="page"
        :items="roles_list"
        item-value="id"
        :items-length="total_roles"
        :headers="headers"
        class="text-no-wrap"
        show-select
        @update:options="updateOptions"
      >
        <!-- User -->
        <template #item.user="{ item }">
          <div class="d-flex align-center gap-x-4">
            <VAvatar
              size="34"
              :variant="!item.avatar ? 'tonal' : undefined"
              :color="
                !item.avatar
                  ? resolveUserRoleVariant(item.role).color
                  : undefined
              "
            >
              <VImg v-if="item.avatar" :src="item.avatar" />
              <span v-else>{{ avatarText(item.fullName) }}</span>
            </VAvatar>
            <div class="d-flex flex-column">
              <h6 class="text-base">
                <RouterLink
                  :to="{ name: 'apps-user-view-id', params: { id: item.id } }"
                  class="font-weight-medium text-link"
                >
                  {{ item.first_name + " " + item.last_name }}
                </RouterLink>
              </h6>
              <div class="text-sm">
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
              :icon="resolveUserRoleVariant(item.name).icon"
              :color="resolveUserRoleVariant(item.name).color"
            />

            <div class="text-capitalize text-high-emphasis text-body-1">
              {{ item.name }}
            </div>
          </div>
        </template>

        <template #item.description="{ item }">
          <div class="text-body-2">
            {{ item.description }}
          </div>
        </template>

        <!-- Actions -->
        <template #item.actions="{ item }">
          <template
            v-if="
              (permsArray.includes('roles_delete') ||
                permsArray.includes('admin')) &&
              item.name !== 'admin'
            "
          >
            <IconBtn @click="fetchRoleById(item.id)">
              <VIcon icon="tabler-pencil" />
            </IconBtn>
          </template>
        </template>

        <!-- pagination -->
        <template #bottom>
          <TablePagination
            v-model:page="page"
            :items-per-page="itemsPerPage"
            :total-items="total_roles"
          />
        </template>
      </VDataTableServer>
      <!-- SECTION -->
    </VCard>
    <!-- Add User Drawer -->
    <AddNewRoleDrawer
      v-model:is-drawer-open="isAddNewRoleDrawerVisible"
      @role-data="addNewRole"
    />

    <!-- Add User Drawer -->
    <EditRoleDrawer
      v-model:is-drawer-open="editRoleDrawerVisible"
      :selected-role="selectedRole"
      @updated-role-data="updateRole"
    />
  </section>
</template>
