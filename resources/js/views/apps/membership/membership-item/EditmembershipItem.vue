<script setup>
import axiosAdmin from "@/composables/axios/axiosAdmin";
import { onMounted, ref } from "vue";
import { PerfectScrollbar } from "vue3-perfect-scrollbar";

// Props and Emits
const props = defineProps({
  isDrawerOpen: {
    type: Boolean,
    required: true,
  },
  selectedMembershipItem: { type: Object, default: () => ({}) },
});

const emit = defineEmits(["update:isDrawerOpen", "membership"]);

// Form validation and ref
const isFormValid = ref(false);
const refForm = ref();

// Dropdown options
const membership_types = ref([]);
const status_options = ref([
  { title: "Active", value: "active" },
  { title: "Inactive", value: "inactive" },
  { title: "Archived", value: "archived" },
]);
const paid_freeze_allowed_options = ref([
  { title: "Yes", value: "YES" },
  { title: "No", value: "NO" },
]);

// 👉 Fetch membership types
onMounted(async () => {
  try {
    const response = await axiosAdmin.get("/membership-types", {
      params: {
        itemsPerPage: -1,
      },
    });
    membership_types.value = response.data;
  } catch (error) {
    console.error("Error fetching membership types:", error);
  }
});

// 👉 Close drawer and reset form
const closeNavigationDrawer = () => {
  emit("update:isDrawerOpen", false);
  nextTick(() => {
    refForm.value?.reset();
    refForm.value?.resetValidation();
    resetFormFields();
  });
};

// 👉 Handle drawer model value update
const handleDrawerModelValueUpdate = (val) => {
  emit("update:isDrawerOpen", val);
};

// 👉 Form submission
const onSubmit = () => {
  clearAllServerErrors();
  refForm.value?.validate().then(({ valid }) => {
    if (valid) {

      const formattedMembershipItemData = {
        id: props.selectedMembershipItem.id ?? null,
        membership_name: props.selectedMembershipItem.membership_name,
        description: props.selectedMembershipItem.description ?? "", // Handle null values
        membership_type_id: props.selectedMembershipItem.membership_type_id,
        duration_days: props.selectedMembershipItem.duration_days,
        price: props.selectedMembershipItem.price,
        free_freezes_allowed: props.selectedMembershipItem.free_freezes_allowed,
        freeze_duration_max_weeks:
          props.selectedMembershipItem.freeze_duration_max_weeks,
        upgradable: props.selectedMembershipItem.upgradable,
        discount_available: props.selectedMembershipItem.discount_available,
        installment_available:
          props.selectedMembershipItem.installment_available,
        gym_access: props.selectedMembershipItem.gym_access,
        status: props.selectedMembershipItem.status,
        paid_freeze_allowed: props.selectedMembershipItem.paid_freeze_allowed,
      };

      axiosAdmin
        .patch(`/membership-items/${formattedMembershipItemData.id}`, formattedMembershipItemData)
        .then(function (response) {
          emit("membership", {
            value: true,
          });

          emit("update:isDrawerOpen", false);
          nextTick(() => {
            refForm.value?.reset();
            refForm.value?.resetValidation();
          });
        })
        .catch(function (error) {
          handleServerErrors(error);
          // Trigger re-validation to show server errors
          refForm.value?.validate();
        });
    }
  });
};
</script>

<template>
  <VNavigationDrawer
    data-allow-mismatch
    temporary
    :width="800"
    location="end"
    class="scrollable-content"
    :model-value="props.isDrawerOpen"
    @update:model-value="handleDrawerModelValueUpdate"
  >
    <!-- 👉 Title -->
    <AppDrawerHeaderSection
      title="Add Membership Item"
      @cancel="closeNavigationDrawer"
    />
    <VDivider />

    <PerfectScrollbar :options="{ wheelPropagation: false }">
      <VCard flat>
        <VCardText>
          <!-- 👉 Form -->
          <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
            <VRow>
              <!-- 👉 Membership Name -->
              <VCol cols="12">
                <AppTextField
                  v-model="selectedMembershipItem.membership_name"
                  :rules="[requiredValidator,serverErrorValidator('membership_name')]"
                  label="Membership Name"
                  placeholder="Gym Membership"
                />
              </VCol>

              <VCol cols="12">
                <AppSelect
                  v-model="selectedMembershipItem.membership_type_id"
                  :items="membership_types"
                  :rules="[requiredValidator,serverErrorValidator('membership_type_id')]"
                  item-title="membership_type"
                  item-value="id"
                  label="Membership Type"
                  placeholder="Select a membership type"
                />
              </VCol>

              <!-- 👉 Membership Duration (Days) -->
              <VCol cols="12">
                <AppTextField
                  v-model="selectedMembershipItem.duration_days"
                  :rules="[requiredValidator, integerValidator,serverErrorValidator('duration_days')]"
                  label="Membership Duration (Days)"
                  placeholder="Membership Duration (Days)"
                />
              </VCol>

              <!-- 👉 Switches -->
              <VCol cols="12">
                <VSwitch
                  v-model="selectedMembershipItem.upgradable"
                  :true-value="1"
                  :false-value="0"
                  :label="`Can Be Upgraded?`"
                />
              </VCol>

              <!-- 👉 Membership Price -->
              <VCol cols="12">
                <AppTextField
                  v-model="selectedMembershipItem.price"
                  :rules="[requiredValidator, decimalValidator]"
                  label="Membership Price"
                  placeholder="Membership Price"
                />
              </VCol>

              <VCol cols="12">
                <VSwitch
                  v-model="selectedMembershipItem.discount_available"
                  :label="`Discount Available?`"
                  :true-value="1"
                  :false-value="0"
                />
              </VCol>
              <VCol cols="12">
                <VSwitch
                  v-model="selectedMembershipItem.installment_available"
                  :label="`Installment Option?`"
                  :true-value="1"
                  :false-value="0"
                />
              </VCol>

              <VCol cols="12">
                <VSwitch
                  v-model="selectedMembershipItem.gym_access"
                  :label="`Includes Gym Access?`"
                  :true-value="1"
                  :false-value="0"
                />
              </VCol>

              <!-- 👉 Total Free Freeze Weeks Allowed -->
              <VCol cols="12">
                <AppTextField
                  v-model="selectedMembershipItem.free_freezes_allowed"
                  :rules="[requiredValidator, integerValidator,serverErrorValidator('free_freezes_allowed')]"
                  label="Total Free Freeze Weeks Allowed"
                  placeholder="Total Free Freeze Weeks Allowed"
                />
              </VCol>

              <!-- 👉 Maximum Freeze Duration (Weeks) -->
              <VCol cols="12">
                <AppTextField
                  v-model="selectedMembershipItem.freeze_duration_max_weeks"
                  :rules="[requiredValidator, integerValidator,serverErrorValidator('freeze_duration_max_weeks')]"
                  label="Maximum Freeze Duration (Weeks)"
                  placeholder="Maximum Freeze Duration (Weeks)"
                />
              </VCol>

              <!-- 👉 Paid Freeze Allowed -->
              <VCol cols="12">
                <AppSelect
                  v-model="selectedMembershipItem.paid_freeze_allowed"
                  :rules="[requiredValidator,serverErrorValidator('freeze_duration_max_weeks')]"
                  :items="paid_freeze_allowed_options"
                  item-title="title"
                  item-value="value"
                  label="Paid Freeze Allowed"
                  placeholder="Paid Freeze Allowed"
                />
              </VCol>

              <!-- 👉 Status -->
              <VCol cols="12">
                <AppSelect
                  v-model="selectedMembershipItem.status"
                  :rules="[requiredValidator]"
                  :items="status_options"
                  item-title="title"
                  item-value="value"
                  label="Status"
                  placeholder="Select a status"
                />
              </VCol>

              <!-- 👉 Description -->
              <VCol cols="12">
                <AppTextarea
                  v-model="selectedMembershipItem.description"
                  label="Description"
                  placeholder="Manage user-related actions"
                  rows="2"
                />
              </VCol>

              <!-- 👉 Submit and Cancel -->
              <VCol cols="12">
                <VBtn type="submit" class="me-3"> Submit </VBtn>
                <VBtn
                  type="reset"
                  variant="tonal"
                  color="error"
                  @click="closeNavigationDrawer"
                >
                  Cancel
                </VBtn>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </PerfectScrollbar>
  </VNavigationDrawer>
</template>

<style scoped>
/* Add custom styles if needed */
</style>
