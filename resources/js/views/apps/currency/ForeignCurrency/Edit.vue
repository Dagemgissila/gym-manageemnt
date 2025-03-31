<script setup>
import axiosAdmin from "@/composables/axios/axiosAdmin";
import { toast } from "vue3-toastify";
const isFormValid = ref(false);
const refForm = ref();
const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
  selectedForeignCurrency: { type: Object, default: () => ({}) },
});

const emit = defineEmits(["update:isDialogVisible", "foreignCurrency"]);


const onSubmit = () => {
  clearAllServerErrors();

  refForm.value?.validate().then(({ valid }) => {
    if (valid) {
      axiosAdmin
      .patch(`/foreign-currencies/${props.selectedForeignCurrency.id}`, props.selectedForeignCurrency)
        .then((response) => {
          toast("Foreign Currency Updated successfully!", {
            theme: "colored",
            type: "success",
            position: "top-right",
            dangerouslyHTMLString: true,
          });

          emit("update:isDialogVisible", false);
          emit("foreignCurrency", true);
          nextTick(() => {
            refForm.value?.reset();
            refForm.value?.resetValidation();
          });
        })
        .catch((error) => {
          handleServerErrors(error);
          // Trigger re-validation to show server errors
          refForm.value?.validate();
        });
    }
  });
};
</script>

<template>
  <VDialog
    :width="$vuetify.display.smAndDown ? 'auto' : 900"
    :model-value="props.isDialogVisible"
    @update:model-value="(val) => $emit('update:isDialogVisible', val)"
  >
    <!-- 👉 Dialog close btn -->
    <DialogCloseBtn @click="$emit('update:isDialogVisible', false)" />

    <VCard class="pa-sm-10 pa-2">
      <h2 class="h2 text-center mb-6">Edit Foreign Currency</h2>
      <VCardText>
        <!-- 👉 Form -->
        <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
          <VRow>
            <!-- 👉 Setting Value -->
            <VCol cols="12" md="4">
              <AppTextField
                v-model="selectedForeignCurrency.code"
                :rules="[requiredValidator, serverErrorValidator('code')]"
                label="Code"
                placeholder="Code"
              />
            </VCol>

            <!-- 👉 Setting Value -->
            <VCol cols="12" md="4">
              <AppTextField
                v-model="selectedForeignCurrency.name"
                :rules="[requiredValidator, serverErrorValidator('name')]"
                label="Name"
                placeholder="Name"
              />
            </VCol>

            <!-- 👉 Setting Value -->
            <VCol cols="12" md="4">
              <AppTextField
                v-model="selectedForeignCurrency.symbol"
                :rules="[requiredValidator, serverErrorValidator('symbol')]"
                label="Symbol"
                placeholder="Symbol"
              />
            </VCol>

            <!-- 👉 Setting Value -->
            <VCol cols="12" md="4">
              <AppTextField
                v-model="selectedForeignCurrency.decimal_place"
                :rules="[
                  requiredValidator,
                  integerValidator,
                  serverErrorValidator('decimal_place'),
                ]"
                label="Decimal Place"
                placeholder="decimal Place"
              />
            </VCol>

            <!-- 👉 Submit and Cancel -->
            <VCol cols="12">
              <VBtn type="submit" class="me-3"> Update </VBtn>
            </VCol>
          </VRow>
        </VForm>
      </VCardText>
    </VCard>
  </VDialog>
</template>
