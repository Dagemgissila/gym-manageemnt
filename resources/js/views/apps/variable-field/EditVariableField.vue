<script setup>
import { handleServerErrors } from "@/@core/utils/validators";
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
  selectedVariableField: { type: Object, default: () => ({}) },
});

const emit = defineEmits(["update:isDrawerOpen", "publicRuleData"]);

// Form validation and ref
const isFormValid = ref(false);
const refForm = ref();

const field = ref();
const status = ref(true);
const field_contents = ref();
const field_value = ref(""); // Single field value

// Fetch fields from the server
const fetchFields = async () => {
  try {
    const response = await axiosAdmin.get("/field-contents");

    field_contents.value = response.data.map((field) => ({
      title: field.name, // Use display_name if available, otherwise fallback to name
      value: field.id,
    }));
  } catch (error) {
    console.error("Error fetching users:", error);
  }
};

// 👉 Form submission
const onSubmit = () => {
  clearAllServerErrors();

  refForm.value?.validate().then(({ valid }) => {
    if (valid) {
      axiosAdmin
        .patch(`/variable-fields/${props.selectedVariableField.id}`, {
          id: props.selectedVariableField.id,
          value: props.selectedVariableField.value,
          field_content_id: props.selectedVariableField.field_content.id,
          status: props.selectedVariableField.status,
        })
        .then((response) => {
          toast("Variable Field updated successfully", {
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
          console.log(error);
          handleServerErrors(error);
          refForm.value?.validate();
        });
    }
  });
};

onMounted(() => {
  fetchFields();
});

// 👉 Close drawer and reset form
const closeNavigationDrawer = () => {
  emit("update:isDrawerOpen", false);
  nextTick(() => {
    refForm.value?.reset();
    refForm.value?.resetValidation();
    field_value.value = ""; // Reset the field value
  });
};

// 👉 Handle drawer model value update
const handleDrawerModelValueUpdate = (val) => {
  emit("update:isDrawerOpen", val);
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
      title="Add Variable Field"
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
                  v-model="selectedVariableField.field_content.id"
                  :rules="[requiredValidator, serverErrorValidator('field')]"
                  :items="field_contents"
                  item-title="title"
                  item-value="value"
                  label="Rule"
                  placeholder="Select Field"
                  @update:model-value="clearServerError('field')"
                />
              </VCol>

              <!-- 👉 Field Value -->
              <VCol cols="12">
                <AppTextField
                  v-model="selectedVariableField.value"
                  :rules="[requiredValidator]"
                  label="Field Value"
                  placeholder="Field Value"
                />
              </VCol>

              <!-- 👉 Status -->
              <VCol cols="12">
                <VSwitch
                  v-model="selectedVariableField.status"
                  :label="`Status`"
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
