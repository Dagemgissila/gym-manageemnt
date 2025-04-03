<script setup>
import axiosAdmin from "@/composables/axios/axiosAdmin";
import { useRoute } from "vue-router";
import { toast } from "vue3-toastify";
const isFormValid = ref(false);
const refForm = ref();


const route = useRoute();
const member_id=route.params.id;
const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
});

const emit = defineEmits(["update:isDialogVisible", "fetchMemberVoucher"]);

const form = ref({
  member_id:member_id,
  amount: "",
  validity: "",
  comment: "",
});

const onSubmit = () => {
  clearAllServerErrors();

  refForm.value?.validate().then(({ valid }) => {
    if (valid) {
      axiosAdmin
        .post('/vouchers', form.value)
        .then((response) => {
          toast("Voucher Created successfully!", {
            theme: "colored",
            type: "success",
            position: "top-right",
            dangerouslyHTMLString: true,
          });

          emit("update:isDialogVisible", false);
          emit("fetchMemberVoucher", true);

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
      <h2 class="h2 text-center mb-6">Create Voucher</h2>
      <VCardText>
        <!-- 👉 Form -->
        <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
          <VRow>
            <!-- 👉 Setting Value -->
            <VCol cols="12" md="6">
              <AppTextField
                v-model="form.amount"
                :rules="[requiredValidator, serverErrorValidator('amount')]"
                label="Amount"
                placeholder="Amount"
              />
            </VCol>

            <VCol cols="12" md="6">
              <AppDateTimePicker
                v-model="form.validity"
                :rules="[requiredValidator, serverErrorValidator('validity')]"
                label="Validity "
                placeholder="Select date"
              />
            </VCol>

            <!-- 👉 Setting Value -->
            <VCol cols="12" md="6">
              <AppTextarea
                v-model="form.comment"
                :rules="[
                  requiredValidator,
                  serverErrorValidator('comment'),
                ]"
                label="Comment"
                placeholder="comments"
              />
            </VCol>

            <!-- 👉 Submit and Cancel -->
            <VCol cols="12">
              <VBtn type="submit" class="me-3"> Create </VBtn>
            </VCol>
          </VRow>
        </VForm>
      </VCardText>
    </VCard>
  </VDialog>
</template>
