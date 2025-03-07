<script setup>
import { handleServerErrors } from "@/@core/utils/validators";
import axiosAdmin from "@/composables/axios/axiosAdmin";
import { onMounted, ref } from "vue";
import { PerfectScrollbar } from "vue3-perfect-scrollbar";

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
const field = ref();
const field_contents = ref();

const formKey = ref(Date.now());

// Use single array of objects for better data integrity
const fieldItems = ref([
  {
    id: Date.now(),
    value: "",
    status: true,
  },
]);

// Add new field with default status true
const addFieldValue = () => {
  fieldItems.value.push({
    id: Date.now(),
    value: "",
    status: true, 
  });
};

// Remove field
const removeFieldValue = (index) => {
  if (fieldItems.value.length > 1) {
    fieldItems.value.splice(index, 1);
  }
};

// Reset form fields
const resetFormFields = () => {
  formKey.value = Date.now();
  fieldItems.value = [
    {
      id: Date.now(),
      value: "",
      status: true,
    },
  ];
  field.value = null;
};

const fetchFields = async () => {
  try {
    const response = await axiosAdmin.get("/field-contents");
    field_contents.value = response.data.map((field) => ({
      title: field.name,
      value: field.id,
    }));
  } catch (error) {
    console.error("Error fetching fields:", error);
  }
};

const closeNavigationDrawer = () => {
  emit("update:isDrawerOpen", false);
  resetFormFields();
  refForm.value?.resetValidation();
};

const handleDrawerModelValueUpdate = (val) => {
  emit("update:isDrawerOpen", val);
};

const onSubmit = () => {
  clearAllServerErrors();

  refForm.value?.validate().then(({ valid }) => {
    if (valid) {
      const payload = {
        field_content_id: field.value,
        values: fieldItems.value
          .filter((item) => item.value.trim() !== "")
          .map((item) => ({
            value: item.value.trim(),
            status: item.status,
          })),
      };

      axiosAdmin
        .post("/variable-fields", payload)
        .then((response) => {
          emit("publicRuleData", { value: true });
          closeNavigationDrawer();
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
</script>

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
      title="Add Variable Field"
      @cancel="closeNavigationDrawer"
    />
    <VDivider />

    <PerfectScrollbar :options="{ wheelPropagation: false }">
      <VCard flat>
        <VCardText>
          <VForm
            :key="formKey"
            ref="refForm"
            v-model="isFormValid"
            @submit.prevent="onSubmit"
          >
            <VRow>
              <VCol cols="12">
                <AppSelect
                  v-model="field"
                  :rules="[requiredValidator, serverErrorValidator('field')]"
                  :items="field_contents"
                  item-title="title"
                  item-value="value"
                  label="Rule"
                  placeholder="Select Field"
                  @update:model-value="clearServerError('field')"
                />
              </VCol>

              <VCol cols="12">
                <template v-for="(item, index) in fieldItems" :key="item.id">
                  <VRow class="mb-1">
                    <VCol cols="5">
                      <AppTextField
                        v-model="item.value"
                        :rules="[requiredValidator]"
                        label="Field Value"
                        placeholder="Field Value"
                      />
                    </VCol>
                    <VCol cols="4" class="d-flex align-center">
                      <VSwitch
                        v-model="item.status"
                        label="Active"
                        :true-value="true"
                        :false-value="false"
                        tabindex="-1"
                      />
                    </VCol>
                    <VCol cols="3" class="d-flex align-center gap-2">
                      <VBtn
                        v-if="fieldItems.length > 1"
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

              <VCol cols="12">
                <VBtn type="submit" class="me-3"> Submit </VBtn>
                <VBtn
                  type="button"
                  variant="tonal"
                  color="error"
                  @click="closeNavigationDrawer"
                  @mousedown.prevent
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
