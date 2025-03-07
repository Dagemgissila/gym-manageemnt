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
const membership_type = ref(null);
const duration_days = ref("");
const price = ref("");
const free_freezes_allowed = ref("");
const freeze_duration_max_weeks = ref("");
const upgradable_limit = ref();
const discount_available = ref(false);
const installment_available = ref(false);
const gym_access = ref(true);
const status = ref("active");
const paid_freeze_allowed = ref("NO");
const suspend_after = ref();
const suspend_based_on_balance = ref();
const accessible_days = ref();
const sessions = ref();
const link_access_to_booked_appts=ref();

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
        membership_name: membership_name.value,
        description: description.value,
        membership_type_id: membership_type.value,
        duration_days: duration_days.value,
        price: price.value,
        free_freezes_allowed: free_freezes_allowed.value,
        freeze_duration_max_weeks: freeze_duration_max_weeks.value,
        upgradable_limit: upgradable_limit.value,
        discount_available: discount_available.value,
        installment_available: installment_available.value,
        gym_access: gym_access.value,
        status: status.value,
        paid_freeze_allowed: paid_freeze_allowed.value,
        suspend_based_on_balance: suspend_based_on_balance.value,
        suspend_after: suspend_after.value,
      };

      axiosAdmin
        .post("/membership-items", formattedMembershipItemData)
        .then(function (response) {
          emit("membershipItemData", {
            value: true,
          });

          emit("update:isDrawerOpen", false);
          nextTick(() => {
            refForm.value?.reset();
            refForm.value?.resetValidation();
            discount_available.value = false; // Reset to false
            installment_available.value = false; // Reset to false
            gym_access.value = false; // Reset to false
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

const addMembershipItem = async (membershipData) => {};
</script>

<template>
  <VNavigationDrawer
    data-allow-mismatch
    temporary
    :width="1200"
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
              <VCol cols="4">
                <AppTextField
                  v-model="membership_name"
                  :rules="[
                    requiredValidator,
                    serverErrorValidator('membership_name'),
                  ]"
                  label="Membership Name"
                  placeholder="Gym Membership"
                />
              </VCol>

              <!-- 👉 Membership Type -->
              <VCol cols="4">
                <AppSelect
                  v-model="membership_type"
                  :items="membership_types"
                  :rules="[
                    requiredValidator,
                    serverErrorValidator('membership_type_id'),
                  ]"
                  item-title="membership_type"
                  item-value="id"
                  label="Membership Type"
                  placeholder="Select a membership type"
                />
              </VCol>

              <!-- 👉 Accessible Days -->
              <VCol cols="4">
                <AppTextField
                  v-model="accessible_days"
                  :rules="[
                    requiredValidator,
                    integerValidator,
                    serverErrorValidator('accessible_days'),
                  ]"
                  label="Accessible Days"
                  placeholder="Acccessible Days"
                />
              </VCol>

              <!-- 👉 Sessions  -->
              <VCol cols="4">
                <AppTextField
                  v-model="sessions"
                  :rules="[
                    requiredValidator,
                    integerValidator,
                    serverErrorValidator('sessions'),
                  ]"
                  label="Sessions"
                  placeholder="Sessions"
                />
              </VCol>

              <!-- 👉 Membership Duration (Days) -->
              <VCol cols="4">
                <AppTextField
                  v-model="duration_days"
                  :rules="[
                    requiredValidator,
                    integerValidator,
                    serverErrorValidator('duration_days'),
                  ]"
                  label="Membership Duration (Days)"
                  placeholder="Membership Duration (Days)"
                />
              </VCol>

              <!-- 👉 Membership Price -->
              <VCol cols="4">
                <AppTextField
                  v-model="price"
                  :rules="[
                    requiredValidator,
                    decimalValidator,
                    serverErrorValidator('price'),
                  ]"
                  label="Membership Price"
                  placeholder="Membership Price"
                />
              </VCol>

              <!-- 👉 Suspend Based On -->
              <VCol cols="4">
                <AppTextField
                  v-model="suspend_based_on_balance"
                  :rules="[
                    requiredValidator,
                    integerValidator,
                    serverErrorValidator('price'),
                  ]"
                  label="Suspend Based On Balance"
                  placeholder="Suspend Based On Balance"
                />
              </VCol>

              <!-- 👉 Suspend After -->
              <VCol cols="4">
                <AppTextField
                  v-model="suspend_after"
                  :rules="[
                    integerValidator,
                    decimalValidator,
                    serverErrorValidator('price'),
                  ]"
                  label="Suspend After"
                  placeholder="Suspeend After"
                />
              </VCol>

              <VCol cols="4">
                <VSwitch
                  v-model="discount_available"
                  :label="`Discount Available?`"
                />
              </VCol>
              <VCol cols="4">
                <VSwitch
                  v-model="installment_available"
                  :label="`Installment Option?`"
                />
              </VCol>

              <VCol cols="4">
                <VSwitch v-model="gym_access" :label="`Includes Gym Access?`" />
              </VCol>


              <VCol cols="4">
                <VSwitch v-model="link_access_to_booked_appts" :label="`Link Access to Booked Appts?`" />
              </VCol>
              
              <!-- 👉 Suspend Based On -->
              <VCol cols="4">
                <AppTextField
                  v-model="upgradable_limit"
                  :rules="[
                    requiredValidator,
                    integerValidator,
                    serverErrorValidator('price'),
                  ]"
                  label="Upgrade Limit"
                  placeholder="Upgrade Limit"
                />
              </VCol>
              <!-- 👉 Total Free Freeze Weeks Allowed -->
              <VCol cols="4">
                <AppTextField
                  v-model="free_freezes_allowed"
                  :rules="[requiredValidator, integerValidator]"
                  label="Total Free Freeze Weeks Allowed"
                  placeholder="Total Free Freeze Weeks Allowed"
                />
              </VCol>

              <!-- 👉 Maximum Freeze Duration (Weeks) -->
              <VCol cols="4">
                <AppTextField
                  v-model="freeze_duration_max_weeks"
                  :rules="[
                    requiredValidator,
                    integerValidator,
                    serverErrorValidator('freeze_duration_max_weeks'),
                  ]"
                  label="Maximum Freeze Duration (Weeks)"
                  placeholder="Maximum Freeze Duration (Weeks)"
                />
              </VCol>

              <!-- 👉 Paid Freeze Allowed -->
              <VCol cols="4">
                <AppSelect
                  v-model="paid_freeze_allowed"
                  :rules="[
                    requiredValidator,
                    serverErrorValidator('paid_freeze_allowed'),
                  ]"
                  :items="paid_freeze_allowed_options"
                  item-title="title"
                  item-value="value"
                  label="Paid Freeze Allowed"
                  placeholder="Paid Freeze Allowed"
                />
              </VCol>

              <!-- 👉 Status -->
              <VCol cols="4">
                <AppSelect
                  v-model="status"
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
