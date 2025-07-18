<template>
  <VNavigationDrawer
    temporary
    :width="800"
    location="end"
    class="scrollable-content"
    :model-value="props.isDrawerOpen"
    @update:model-value="handleDrawerModelValueUpdate"
  >
    <AppDrawerHeaderSection
      title="Add New Role"
      @cancel="closeNavigationDrawer"
    />

    <VDivider />
    <PerfectScrollbar :options="{ wheelPropagation: false }">
      <VCard flat>
        <VCardText>
          <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
            <VRow>
              <VCol cols="6">
                <AppTextField
                  v-model="role_name"
                  :rules="[requiredValidator]"
                  label="Role Name"
                  placeholder="user"
                />
              </VCol>
              <VCol cols="12">
                <VSwitch
                  v-model="is_trainer"
                  :true-value="1"
                  :false-value="0"
                  :label="`Is Trainer`"
                />
              </VCol>
              <VCol cols="12">
                <AppTextarea
                  v-model="description"
                  label="Description"
                  placeholder="Manage user-related actions"
                  rows="2"
                />
              </VCol>

              <!-- 👉 Permissions -->
              <VCol cols="12">
                <VTable class="w-full text-no-wrap">
                  <thead>
                    <tr>
                      <th colspan="5">Permissions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <!-- Users Permissions -->
                    <tr>
                      <td>Users</td>
                      <td>
                        <VCheckbox
                          v-model="checkedPermissions"
                          :value="permissions.users_view"
                          label="View"
                        />
                      </td>
                      <td>
                        <VCheckbox
                          v-model="checkedPermissions"
                          :value="permissions.users_create"
                          label="Add"
                        />
                      </td>
                      <td>
                        <VCheckbox
                          v-model="checkedPermissions"
                          :value="permissions.users_edit"
                          label="Edit"
                        />
                      </td>
                      <td>
                        <VCheckbox
                          v-model="checkedPermissions"
                          :value="permissions.users_delete"
                          label="Delete"
                        />
                      </td>
                    </tr>

                    <!-- Role and Permissions -->
                    <tr>
                      <td>Roles and Permissions</td>
                      <td>
                        <VCheckbox
                          v-model="checkedPermissions"
                          :value="permissions.roles_view"
                          label="View"
                        />
                      </td>
                      <td>
                        <VCheckbox
                          v-model="checkedPermissions"
                          :value="permissions.roles_create"
                          label="Add"
                        />
                      </td>
                      <td>
                        <VCheckbox
                          v-model="checkedPermissions"
                          :value="permissions.roles_edit"
                          label="Edit"
                        />
                      </td>
                      <td>
                        <VCheckbox
                          v-model="checkedPermissions"
                          :value="permissions.roles_delete"
                          label="Delete"
                        />
                      </td>
                    </tr>

                    <!-- Membership Type -->
                    <tr>
                      <td>Membership Type</td>
                      <td>
                        <VCheckbox
                          v-model="checkedPermissions"
                          :value="permissions.membership_type_view"
                          label="View"
                        />
                      </td>
                      <td>
                        <VCheckbox
                          v-model="checkedPermissions"
                          :value="permissions.membership_type_create"
                          label="Add"
                        />
                      </td>
                      <td>
                        <VCheckbox
                          v-model="checkedPermissions"
                          :value="permissions.membership_type_edit"
                          label="Edit"
                        />
                      </td>
                      <td>
                        <VCheckbox
                          v-model="checkedPermissions"
                          :value="permissions.membership_type_delete"
                          label="Delete"
                        />
                      </td>
                    </tr>

                    <!-- Membership Type -->
                    <tr>
                      <td>Membership Item</td>
                      <td>
                        <VCheckbox
                          v-model="checkedPermissions"
                          :value="permissions.membership_item_view"
                          label="View"
                        />
                      </td>
                      <td>
                        <VCheckbox
                          v-model="checkedPermissions"
                          :value="permissions.membership_item_create"
                          label="Add"
                        />
                      </td>
                      <td>
                        <VCheckbox
                          v-model="checkedPermissions"
                          :value="permissions.membership_item_edit"
                          label="Edit"
                        />
                      </td>
                      <td>
                        <VCheckbox
                          v-model="checkedPermissions"
                          :value="permissions.membership_item_delete"
                          label="Delete"
                        />
                      </td>
                    </tr>
                  </tbody>
                </VTable>
              </VCol>

              <VCol cols="12">
                <VBtn type="submit" class="me-3">Submit</VBtn>
                <VBtn
                  type="reset"
                  variant="tonal"
                  color="error"
                  @click="closeNavigationDrawer"
                  >Cancel</VBtn
                >
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </PerfectScrollbar>
  </VNavigationDrawer>
</template>

<script setup>
import axiosAdmin from "@/composables/axios/axiosAdmin";
import { nextTick, onMounted, ref, watch } from "vue";
import { PerfectScrollbar } from "vue3-perfect-scrollbar";

const props = defineProps({
  isDrawerOpen: { type: Boolean, required: true },
  selectedRole: { type: Object, default: () => ({}) },
});

const emit = defineEmits(["update:isDrawerOpen", "updatedRoleData"]);

const isFormValid = ref(false);
const refForm = ref();
const role_name = ref("");
const is_trainer = ref(true);
const description = ref("");

const permissions = ref({});
const checkedPermissions = ref([]);

const fetchPermissions = async () => {
  try {
    const response = await axiosAdmin.get("/permissions");
    permissions.value = response.data.reduce((acc, res) => {
      acc[res.name] = res.id;
      return acc;
    }, {});
  } catch (error) {
    console.error("Error fetching permissions:", error);
  }
};

const closeNavigationDrawer = () => {
  emit("update:isDrawerOpen", false);
  nextTick(() => {
    refForm.value?.reset();
    refForm.value?.resetValidation();
    checkedPermissions.value = [];
  });
};

watch(
  () => props.selectedRole,

  (newRole) => {
    if (newRole && Object.keys(newRole).length > 0) {
      role_name.value = newRole.name || "";
      description.value = newRole.description || "";
      is_trainer.value = newRole.is_trainer ?? 0;
      checkedPermissions.value = newRole.permissions.map(
        (permission) => permission.id
      );
    } else {
      checkedPermissions.value = [];
      is_trainer.value = 0;
    }
  },
  { deep: true, immediate: true }
);

const onSubmit = () => {
  refForm.value?.validate().then(({ valid }) => {
    if (valid) {
      console.log(is_trainer.value);
      emit("updatedRoleData", {
        id: props.selectedRole.id ?? null,
        role_name: role_name.value,
        description: description.value,
        is_trainer: is_trainer.value,
        permissions: checkedPermissions.value,
      });

      closeNavigationDrawer();
    }
  });
};

const handleDrawerModelValueUpdate = (val) => {
  emit("update:isDrawerOpen", val);
};

onMounted(fetchPermissions);
</script>
