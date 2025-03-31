<script setup>
import { decimalValidator } from "@/@core/utils/validators";
import axiosAdmin from "@/composables/axios/axiosAdmin";
import { computed, nextTick, onMounted, ref, watch } from "vue";
import { toast } from "vue3-toastify";

const isFormValid = ref(false);
const refForm = ref();
const foreign_currencies = ref([]);
const base_currency = ref();

const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
  selectedExchangeRate: { type: Object, default: () => ({}) },
});

const emit = defineEmits(["update:isDialogVisible", "foreignCurrency"]);

const form = ref({
  date: "",
  base_currency_id: "",
  foreign_currency_id: "",
  exchange_rate: "",
});

watch(
  () => props.selectedExchangeRate,
  (newVal) => {
    if (newVal && Object.keys(newVal).length) {
      form.value = { ...newVal }; // Only update if new data is available
    }
  },
  { deep: true, immediate: true } 
);

const selectedForeignCurrency = computed(() => {
  const currency = foreign_currencies.value.find(
    (currency) => currency.id == form.value.foreign_currency_id
  );
  return currency ? currency.code : null;
});

const onSubmit = () => {
  clearAllServerErrors();

  refForm.value?.validate().then(({ valid }) => {
    if (valid) {
      axiosAdmin
        .patch(`/exchange-rates/${form.value.id}`, form.value)
        .then(() => {
          toast("Exchange Rate Updated successfully!", {
            theme: "colored",
            type: "success",
            position: "top-right",
            dangerouslyHTMLString: true,
          });

          emit("update:isDialogVisible", false);
          emit("foreignExchangeRate", true);

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

// Fetch foreign currencies
const fetchForeignCurrency = async () => {
  try {
    const response = await axiosAdmin.get("/foreign-currencies", {
      params: { itemsPerPage: -1 },
    });
    foreign_currencies.value = response.data.map((currency) => ({
      id: currency.id,
      title: `${currency.name} - ${currency.code}`,
      value: currency.id,
      code: currency.code,
    }));
  } catch (error) {
    console.error("Error fetching foreign currencies:", error);
  }
};

// Fetch base currency
const getBaseCurrency = async () => {
  try {
    const response = await axiosAdmin.get("/base-currency");
    const data = response.data;
    base_currency.value = data[Object.keys(data)[0]]; // Get the first item
    form.value.base_currency_id = base_currency.value.id;
  } catch (error) {
    console.error("Error fetching base currency:", error);
  }
};

onMounted(() => {
  fetchForeignCurrency();
  getBaseCurrency();
});
</script>

<template>
  <VDialog
    :width="$vuetify.display.smAndDown ? 'auto' : 900"
    :model-value="isDialogVisible"
    @update:model-value="(val) => $emit('update:isDialogVisible', val)"
  >
    <DialogCloseBtn @click="$emit('update:isDialogVisible', false)" />

    <VCard class="pa-sm-10 pa-2">
      <h2 class="h2 text-center mb-6">Edit Exchange Rate</h2>
      <VCardText>
        <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
          <VRow align="center">
            <VCol cols="12" md="6">
              <AppDateTimePicker
                v-model="form.date"
                :rules="[requiredValidator, serverErrorValidator('date')]"
                label="Date"
                placeholder="Select date"
              />
            </VCol>

            <VCol cols="12" md="6">
              <AppSelect
                v-model="form.foreign_currency_id"
                label="Select Foreign Currency"
                :rules="[requiredValidator, serverErrorValidator('foreign_currencies')]"
                :items="foreign_currencies"
                placeholder="Select Foreign Currency"
              />
            </VCol>

            <VCol cols="8" v-if="form.foreign_currency_id">
              <VRow class="d-flex align-center justify-center">
                <VCol cols="3" class="d-flex align-center justify-end">
                  <VBtn variant="outlined" color="grey-darken-1">
                    1 {{ base_currency?.code }}
                  </VBtn>
                </VCol>

                <VCol cols="6">
                  <AppTextField
                    v-model="form.exchange_rate"
                    :rules="[requiredValidator, decimalValidator, serverErrorValidator('Exchange Rate')]"
                    placeholder="Exchange Rate"
                  />
                </VCol>

                <VCol cols="3" class="d-flex align-center">
                  <VBtn variant="outlined" color="grey-darken-1">
                    {{ selectedForeignCurrency }}
                  </VBtn>
                </VCol>
              </VRow>
            </VCol>

            <VCol cols="12">
              <VBtn type="submit" class="me-3">Update</VBtn>
            </VCol>
          </VRow>
        </VForm>
      </VCardText>
    </VCard>
  </VDialog>
</template>
