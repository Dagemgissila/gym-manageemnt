<script setup>
import { ref } from "vue";
import { PerfectScrollbar } from "vue3-perfect-scrollbar";

// Props and Emits
const props = defineProps({
  isDrawerOpen: {
    type: Boolean,
    required: true,
  },
  selectedRole: { type: Object, default: () => ({}) },
});

const emit = defineEmits(["update:isDrawerOpen", "publicRuleData"]);

// Form validation and ref
const isFormValid = ref(false);
const refForm = ref();


const setting_rule = ref();
const setting_value = ref("");
const status = ref("active");

// Dropdown options
const public_rules = ref([
  {title:"Backdated Entry Date"},
  {title:"Upgrade Limit"}
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
  refForm.value?.validate().then(({ valid }) => {
    if (valid) {
      emit("publicRuleData", {
       public_rule: props.selectedRole,

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
                  v-model="selectedRole.setting_rule"
                  :items="public_rules"
                  :rules="[requiredValidator]"
                  
                  item-title="title"
                  item-value="title"
                  label="Rule"
                  placeholder="Select a Rule"
                />
              </VCol>

              <!-- 👉 Setting Value -->
              <VCol cols="12">
                <AppTextField
                  v-model="selectedRole.setting_value"
                  :rules="[requiredValidator, integerValidator]"
                  label="Setting Value"
                  placeholder="Setting Value"
                />
              </VCol>

       
              <!-- 👉 Status -->
              <VCol cols="12">
                <AppSelect
                  v-model="selectedRole.status"
                  :rules="[requiredValidator]"
                  :items="status_options"
                  item-title="title"
                  item-value="value"
                  label="Status"
                  placeholder="Select a status"
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
