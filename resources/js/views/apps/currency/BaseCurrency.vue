<script setup>
import axiosAdmin from "@/composables/axios/axiosAdmin";
import { onMounted, ref, watch } from "vue";
import { toast } from "vue3-toastify";


const props = defineProps({
  isActive: Boolean,
});


const isFormValid = ref(false);
const refForm = ref();
const base_currency = ref(null);

const form = ref({
  id:"",
  code: "",
  name: "",
  symbol: "",
  decimal_place: "",
});

// Watch for base_currency changes and update form
watch(base_currency, (newVal) => {
  if (newVal) {
    form.value = { ...newVal };
  }
});

// 👉 Form submission
const onSubmit = () => {
  refForm.value?.validate().then(({ valid }) => {
    if (valid) {
      axiosAdmin.put(`/base-currency/${form.value.id}`, form.value)
        .then(() => {
          toast("Base currency updated successfully!", {
            theme: "colored",
            type: "success",
            position: "top-right",
            dangerouslyHTMLString: true,
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

function getBaseCurrency() {
  axiosAdmin
    .get("/base-currency")
    .then((response) => {
      const data = response.data;
      base_currency.value = data[Object.keys(data)[0]]; // Get the first item
      form.value = { ...base_currency.value };

    })
    .catch((error) => {
      console.log(error);
    });
}

onMounted(getBaseCurrency);
</script>


<template>
  <VCard flat>
    <VCardText>
      <!-- 👉 Form -->
      <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
        <VRow>
          <!-- 👉 Setting Value -->
          <VCol cols="12" md="4">
            <AppTextField
              v-model="form.code"
              :rules="[requiredValidator, serverErrorValidator('code')]"
              label="Code"
              placeholder="Code"
            />
          </VCol>

          <!-- 👉 Setting Value -->
          <VCol cols="12" md="4">
            <AppTextField
              v-model="form.name"
              :rules="[requiredValidator, serverErrorValidator('name')]"
              label="Name"
              placeholder="Name"
            />
          </VCol>

          <!-- 👉 Setting Value -->
          <VCol cols="12" md="4">
            <AppTextField
              v-model="form.symbol"
              :rules="[requiredValidator, serverErrorValidator('symbol')]"
              label="Symbol"
              placeholder="Symbol"
            />
          </VCol>

          <!-- 👉 Setting Value -->
          <VCol cols="12" md="4">
            <AppTextField
              v-model="form.decimal_place"
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
</template>

<style lang="scss"></style>
