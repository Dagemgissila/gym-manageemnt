<script setup>
import axiosAdmin from "@/composables/axios/axiosAdmin";
import { ref } from "vue";
import { PerfectScrollbar } from "vue3-perfect-scrollbar";
import { toast } from "vue3-toastify";
// Props and Emits
const props = defineProps({
  isDrawerOpen: {
    type: Boolean,
    required: true,
  },
  selectedRule: { type: Object, default: () => ({}) },
});

const emit = defineEmits(["update:isDrawerOpen", "publicRuleData"]);

// Form validation and ref
const isFormValid = ref(false);
const refForm = ref();

// Dropdown options
const public_rules = ref([
  { title: "Backdated Entry Date" },
  { title: "Upgrade Limit" },
]);
const status_options = ref([
  { title: "Active", value: "active" },
  { title: "Inactive", value: "inactive" },
]);

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
      axiosAdmin
        .patch(`/public-rules/${props.selectedRule.id}`, props.selectedRule)
        .then((response) => {
          toast("Rule updated successfully", {
        theme: "colored",
        type: "success",
        position: "top-right",
        dangerouslyHTMLString: true,
      });
          emit("publicRuleData", {
            value: true,
          });
          emit("update:isDrawerOpen", false);
          nextTick(() => {
            refForm.value?.reset();
            refForm.value?.resetValidation();
          });
        })
        .catch((error) => {
          handleServerErrors(error);
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
      title="Add Public Rule"
      @cancel="closeNavigationDrawer"
    />
    <VDivider />

    <PerfectScrollbar :options="{ wheelPropagation: false }">
      <VCard flat>
        <VCardText>
          <!-- 👉 Form -->
          <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
            <VRow>
              <!-- 👉 Rule -->
              <VCol cols="12">
                <AppSelect
                  v-model="selectedRule.setting_rule"
                  :items="public_rules"
                  :rules="[
                    requiredValidator,
                    serverErrorValidator('setting_rule'),
                  ]"
                  item-title="title"
                  item-value="title"
                  label="Rule"
                  placeholder="Select a Rule"
                />
              </VCol>

              <!-- 👉 Setting Value -->
              <VCol cols="12">
                <AppTextField
                  v-model="selectedRule.setting_value"
                  :rules="[requiredValidator, integerValidator]"
                  label="Setting Value"
                  placeholder="Setting Value"
                />
              </VCol>

              <!-- 👉 Status -->
              <VCol cols="6">
                <VSwitch
                  v-model="selectedRule.status"
                  :label="`User Status`"
                  :true-value="1"
                  :false-value="0"
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
