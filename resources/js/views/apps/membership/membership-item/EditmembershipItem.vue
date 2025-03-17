<script setup>
import axiosAdmin from "@/composables/axios/axiosAdmin";
import { computed, onMounted, ref, watch } from "vue";
import { PerfectScrollbar } from "vue3-perfect-scrollbar";
import EditDayTimeRestriction from "./dialog/EditDayTimeRestriction.vue";
// Props and Emits
const props = defineProps({
  isDrawerOpen: {
    type: Boolean,
    required: true,
  },
  selectedMembershipItem: { type: Object, default: () => ({}) },
});



const isCardAddDialogVisible = ref(false)



// Local editable state
const localMembership = ref({
  membership_name: "",
  description: "",
  membership_type_id: null,
  duration_days: "",
  price: "",
  free_freezes_allowed: "",
  freeze_duration_max_weeks: "",
  upgradable_limit: "",
  discount_available: false,
  installment_available: false,
  gym_access: false,
  status: false,
  paid_freeze_allowed: "NO",
  suspend_based_on_balance: "",
  suspend_after: "",
  accessible_days: "",
  sessions: "",
  link_access_to_booked_appts: false,
  selected_days:[]
});

// Dropdown options
const membership_types = ref([]);

const paid_freeze_allowed_options = ref([
  { title: "Yes", value: "YES" },
  { title: "No", value: "NO" },
]);

// Watchers
watch(
  () => props.selectedMembershipItem,
  (newVal) => {
    if (newVal.id) {
      localMembership.value = {
        ...newVal,
        membership_type_id: newVal.membership_type?.id,
        gym_access: Boolean(newVal.gym_access),
        discount_available: Boolean(newVal.discount_available),
        installment_available: Boolean(newVal.installment_available),
        status: Boolean(newVal.status),

        link_access_to_booked_appts: Boolean(
          newVal.link_access_to_booked_appts
        ),
      };
    }
  },
  { immediate: true, deep: true }
);

watch(
  () => localMembership.value.gym_access,
  (newVal) => {
    if (newVal) {
      localMembership.value.link_access_to_booked_appts = false;
    }
  }
);
// Computed properties
const showLinkAccess = computed(() => {
  if (localMembership.value.gym_access) return false;

  const selectedType = membership_types.value.find(
    (type) => type.id === localMembership.value.membership_type_id
  );

  return ["Classes Based", "Session Based"].includes(
    selectedType?.membership_base
  );
});

const emit = defineEmits(["update:isDrawerOpen", "membershipItemData"]);

// Form validation and ref
const isFormValid = ref(false);
const refForm = ref();

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
      const payload = {
        ...localMembership.value,
        gym_access: localMembership.value.gym_access ? 1 : 0,
        discount_available: localMembership.value.discount_available ? 1 : 0,
        status: localMembership.value.status ? 1 : 0,
        selected_days: localMembership.value.selected_days,

        installment_available: localMembership.value.installment_available
          ? 1
          : 0,
        link_access_to_booked_appts: localMembership.value
          .link_access_to_booked_appts
          ? 1
          : 0,
      };

      axiosAdmin
        .patch(`/membership-items/${localMembership.value.id}`, payload)
        .then(function (response) {
          emit("membershipItemData", {
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



function dateTimeRestriction(data) {
  localMembership.value.selected_days = [...data];
  console.log("this is formatted data", data);
}

const removeSelectedDay = (index) => {
  localMembership.value.selected_days = localMembership.value.selected_days.filter((_, i) => i !== index);
};

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
      title="Edit Membership Item"
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
              <VCol cols="12" md="4">
                <AppTextField
                  v-model="localMembership.membership_name"
                  :rules="[
                    requiredValidator,
                    serverErrorValidator('membership_name'),
                  ]"
                  label="Membership Name"
                  placeholder="Gym Membership"
                />
              </VCol>

              <!-- Membership Type -->
              <VCol cols="12" md="4">
                <AppSelect
                  v-model="localMembership.membership_type_id"
                  :items="membership_types"
                  item-title="membership_type"
                  item-value="id"
                  label="Membership Type"
                  :rules="[requiredValidator]"
                />
              </VCol>

              <!-- 👉 Accessible Days -->
              <VCol cols="12" md="4">
                <AppTextField
                  v-model="localMembership.accessible_days"
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
              <VCol cols="12" md="4">
                <AppTextField
                  v-model="localMembership.sessions"
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
              <VCol cols="12" md="4">
                <AppTextField
                  v-model="localMembership.duration_days"
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
              <VCol cols="12" md="4">
                <AppTextField
                  v-model="localMembership.price"
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
              <VCol cols="12" md="4">
                <AppTextField
                  v-model="localMembership.suspend_based_on_balance"
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
              <VCol cols="12" md="4">
                <AppTextField
                  v-model="localMembership.suspend_after"
                  :rules="[
                    integerValidator,
                    decimalValidator,
                    serverErrorValidator('price'),
                  ]"
                  label="Suspend After"
                  placeholder="Suspeend After"
                />
              </VCol>

              <VCol cols="12" md="4">
                <VSwitch
                  v-model="localMembership.discount_available"
                  :label="`Discount Available?`"
                  :true-value="true"
                  :false-value="false"
                />
              </VCol>
              <VCol cols="12" md="4">
                <VSwitch
                  v-model="localMembership.installment_available"
                  :label="`Installment Option?`"
                  :true-value="true"
                  :false-value="false"
                />
              </VCol>

              <VCol cols="12" md="4">
                <VSwitch
                  v-model="localMembership.gym_access"
                  :true-value="true"
                  :false-value="false"
                  :label="`Includes Gym Access?`"
                />
              </VCol>


              <VCol cols="12" md="4" v-if="localMembership.gym_access">
                <EditDayTimeRestriction v-model:is-dialog-visible="isCardAddDialogVisible" :selectedDays="localMembership.selected_days"
                  @date-time-restriction="dateTimeRestriction" />

                <VBtn @click="isCardAddDialogVisible = !isCardAddDialogVisible">
                  Date & Time Restriction
                </VBtn>
              </VCol>

              <VCol cols="12" md="4" v-if="showLinkAccess">
                <VSwitch
                  v-model="localMembership.link_access_to_booked_appts"
                  :label="`Link Access to Booked Appts?`"
                  :true-value="true"
                  :false-value="false"
                />
              </VCol>

              <!-- 👉 Suspend Based On -->
              <VCol cols="12" md="4">
                <AppTextField
                  v-model="localMembership.upgradable_limit"
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
              <VCol cols="12" md="4">
                <AppTextField
                  v-model="localMembership.free_freezes_allowed"
                  :rules="[requiredValidator, integerValidator]"
                  label="Total Free Freeze Weeks Allowed"
                  placeholder="Total Free Freeze Weeks Allowed"
                />
              </VCol>

              <!-- 👉 Maximum Freeze Duration (Weeks) -->
              <VCol cols="12" md="4">
                <AppTextField
                  v-model="localMembership.freeze_duration_max_weeks"
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
              <VCol cols="12" md="4">
                <AppSelect
                  v-model="localMembership.paid_freeze_allowed"
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
              <VCol cols="12" md="4">
                <VSwitch
                  v-model="localMembership.status"
                  :true-value="true"
                  :false-value="false"
                  :label="`Status`"
                />
              </VCol>

              <!-- 👉 Description -->
              <VCol cols="12">
                <AppTextarea
                  v-model="localMembership.description"
                  label="Description"
                  placeholder="Manage user-related actions"
                  rows="2"
                />
              </VCol>


              <VCol cols="12">

<VTable class="text-no-wrap">
  <thead>
    <tr>
      <th>Day</th>
      <th>From</th>
      <th>To</th>
      <th>Time Period</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <tr v-for="(item, index) in localMembership.selected_days" :key="index">
      <td>{{ item.day }}</td>
      <td>{{ item.from_time }}</td>
      <td>{{ item.to_time }}</td>
      <td>{{ item.time_period }}</td>
      <td>

        <IconBtn @click="removeSelectedDay(index)">
          <VIcon icon="tabler-trash" />
        </IconBtn>
      </td>
    </tr>
    <tr v-if="localMembership.selected_days.length === 0">
      <td colspan="5" class="text-center">No day/time restrictions added</td>
    </tr>
  </tbody>
</VTable>
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
