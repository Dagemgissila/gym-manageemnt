<script setup>
import { handleServerErrors } from "@/@core/utils/validators";
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

const emit = defineEmits(["update:isDrawerOpen", "publicRuleData"]);

// Form validation and ref
const isFormValid = ref(false);
const refForm = ref();
const optionCounter=ref(1);

const field = ref();
const status = ref(true);
const field_contents=ref();


const field_values = ref(['']); // Initialize with one empty field

// Add new field value input
const addFieldValue = () => {
  field_values.value.push('');
};

// Remove field value by index
const removeFieldValue = (index) => {
  if (field_values.value.length > 1) {
    field_values.value.splice(index, 1);
  }
};

// Reset form fields
const resetFormFields = () => {
  field_values.value = [''];
  // ... (reset other fields if needed)
};


const fetchFields = async () => {
  try {
    const response = await axiosAdmin.get("/field-contents",);


    field_contents.value = response.data.map((field) => ({
      title:  field.name, // Use display_name if available, otherwise fallback to name
      value: field.id,
    }));

  } catch (error) {
    console.error("Error fetching users:", error);
  }
};



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

      const values = field_values.value.filter(v => v.trim() !== '');
      
      axiosAdmin
        .post("/variable-fields", {
          field_content_id: field.value,
          values: values,  // Send the array values, not the ref object
          status: status.value,
        })

        .then((response) => {
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
          console.log(error)
          handleServerErrors(error)
          refForm.value?.validate();

        });
    }
  });
};

onMounted(()=>{
  fetchFields();
})
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
                  v-model="field"
                  :rules="[requiredValidator,serverErrorValidator('field')]"
                  :items="field_contents"
                  item-title="title"
                  item-value="value"
                  label="Rule"
                  placeholder="Select Field"
                  @update:model-value="clearServerError('field')"

                />
              </VCol>

          <!-- 👉 Field Values -->
          <VCol cols="12">
            <template v-for="(value, index) in field_values" :key="index">
              <VRow class="mb-1">
                <VCol cols="10" md="8">
                  <AppTextField
                    v-model="field_values[index]"
                    :rules="[requiredValidator]"
                    label="Field Value"
                    placeholder="Field Value"
                  />
                </VCol>
                <VCol cols="2" md="4" class="d-flex align-center">
                  <VBtn
                    v-if="field_values.length > 1"
                    icon
                    variant="text"
                    color="error"
                    @click="removeFieldValue(index)"
                  >
                    <VIcon icon="tabler-x" />
                  </VBtn>
                </VCol>
              </VRow>
            </template>

            <VBtn
              class="mt-2"
              prepend-icon="tabler-plus"
              @click="addFieldValue"
            >
              Add another option
            </VBtn>
          </VCol>

              <!-- 👉 Status -->
              <VCol cols="12">
                <VSwitch
                  v-model="status"
                  :label="`Status`"
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
