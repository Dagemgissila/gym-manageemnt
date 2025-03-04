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
});

const emit = defineEmits(["update:isDrawerOpen", "membershipItemData"]);

// Form validation and ref
const isFormValid = ref(false);
const refForm = ref();

// Form fields
const membership_name = ref("");
const description = ref("");
const selected_membership_type = ref(null);
const membership_duration_days = ref("");
const membership_price = ref("");
const total_free_freeze_weeks_allowed = ref("");
const maximum_freeze_duration_weeks = ref("");
const is_upgradable = ref(false);
const is_discount_available = ref(false);
const is_installment_option = ref(false);
const includes_gym_access = ref(false);
const is_session_based = ref(false);
const is_live_membership = ref(false);
const is_membership_overlap = ref(false);
const selected_status = ref("active");
const selected_freeze_status = ref("NO");

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
  refForm.value?.validate().then(({ valid }) => {
    if (valid) {
      emit("membershipItemData", {
        membership_name: membership_name.value,
        description: description.value,
        selected_membership_type: selected_membership_type.value,
        membership_duration_days: membership_duration_days.value,
        membership_price: membership_price.value,
        total_free_freeze_weeks_allowed: total_free_freeze_weeks_allowed.value,
        maximum_freeze_duration_weeks: maximum_freeze_duration_weeks.value,
        is_upgradable: is_upgradable.value,
        is_discount_available: is_discount_available.value,
        is_installment_option: is_installment_option.value,
        includes_gym_access: includes_gym_access.value,
        selected_status: selected_status.value,
        selected_freeze_status: selected_freeze_status.value,
      });
      emit("update:isDrawerOpen", false);
      nextTick(() => {
        refForm.value?.reset();
        refForm.value?.resetValidation();
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
                  v-model="membership_name"
                  :rules="[requiredValidator]"
                  label="Membership Name"
                  placeholder="Gym Membership"
                />
              </VCol>

              <!-- 👉 Membership Type -->
              <VCol cols="12">
                <AppSelect
                  v-model="selected_membership_type"
                  :items="membership_types"
                  :rules="[requiredValidator]"
                  item-title="membership_type"
                  item-value="id"
                  label="Membership Type"
                  placeholder="Select a membership type"
                />
              </VCol>

              <!-- 👉 Membership Duration (Days) -->
              <VCol cols="12">
                <AppTextField
                  v-model="membership_duration_days"
                  :rules="[requiredValidator, integerValidator]"
                  label="Membership Duration (Days)"
                  placeholder="Membership Duration (Days)"
                />
              </VCol>

              <!-- 👉 Switches -->
              <VCol cols="12">
                <VSwitch v-model="is_upgradable" :label="`Can Be Upgraded?`" />
              </VCol>

              <!-- 👉 Membership Price -->
              <VCol cols="12">
                <AppTextField
                  v-model="membership_price"
                  :rules="[requiredValidator, decimalValidator]"
                  label="Membership Price"
                  placeholder="Membership Price"
                />
              </VCol>

              <VCol cols="12">
                <VSwitch
                  v-model="is_discount_available"
                  :label="`Discount Available?`"
                />
              </VCol>
              <VCol cols="12">
                <VSwitch
                  v-model="is_installment_option"
                  :label="`Installment Option?`"
                />
              </VCol>

              <VCol cols="12">
                <VSwitch
                  v-model="includes_gym_access"
                  :label="`Includes Gym Access?`"
                />
              </VCol>

              <!-- 👉 Total Free Freeze Weeks Allowed -->
              <VCol cols="12">
                <AppTextField
                  v-model="total_free_freeze_weeks_allowed"
                  :rules="[requiredValidator, integerValidator]"
                  label="Total Free Freeze Weeks Allowed"
                  placeholder="Total Free Freeze Weeks Allowed"
                />
              </VCol>

              <!-- 👉 Maximum Freeze Duration (Weeks) -->
              <VCol cols="12">
                <AppTextField
                  v-model="maximum_freeze_duration_weeks"
                  :rules="[requiredValidator, integerValidator]"
                  label="Maximum Freeze Duration (Weeks)"
                  placeholder="Maximum Freeze Duration (Weeks)"
                />
              </VCol>

              <!-- 👉 Paid Freeze Allowed -->
              <VCol cols="12">
                <AppSelect
                  v-model="selected_freeze_status"
                  :rules="[requiredValidator]"
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
                  v-model="selected_status"
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
                  v-model="description"
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
