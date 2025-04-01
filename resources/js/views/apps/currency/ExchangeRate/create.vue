<script setup>
import { decimalValidator } from "@/@core/utils/validators";
import axiosAdmin from "@/composables/axios/axiosAdmin";
import { computed, ref } from "vue";
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
});

const emit = defineEmits(["update:isDialogVisible", "foreignCurrency"]);


const form = ref({
  date: new Date().toISOString().split("T")[0],
  base_currency_id: "",
  foreign_currency_id: "",
  exchange_rate: "",
});

const selectedForeignCurrency = computed(() => {
  const currency = foreign_currencies.value.find((currency) => currency.id == form.value.foreign_currency_id);
  return currency ? currency.code : null;
});


const onSubmit = () => {
  clearAllServerErrors();

  refForm.value?.validate().then(({ valid }) => {
    if (valid) {
      axiosAdmin
        .post("/exchange-rates", form.value)
        .then((response) => {
          toast("Exchange Rate Added successfully!", {
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
          // Trigger re-validation to show server errors
          refForm.value?.validate();
        });
    }
  });
};


// 👉 Fetch Role
const fetchForeignCurrency = async () => {
  try {
    const response = await axiosAdmin.get("/foreign-currencies", {
      params: {
        itemsPerPage: -1,
      },
    });
    foreign_currencies.value = response.data.map((currency) => ({
      id: currency.id,
      title: `${currency.name} - ${currency.code}`,
      value: currency.id,
      code: currency.code
    }));
  } catch (error) {
    console.error("Error fetching users:", error);
  }
};


function getBaseCurrency() {
  axiosAdmin
    .get("/base-currency")
    .then((response) => {
      console.log(response);
      const data = response.data;
      base_currency.value = data[Object.keys(data)[0]]; // Get the first item
      form.value.base_currency_id = base_currency.value.id;

    })
    .catch((error) => {
      console.log(error);
    });
}

const fetchData = () => {
    fetchForeignCurrency();
    getBaseCurrency();
};


</script>

<template>
  <VDialog :width="$vuetify.display.smAndDown ? 'auto' : 900" :model-value="props.isDialogVisible"
    @update:model-value="(val) => $emit('update:isDialogVisible', val)">
    <!-- 👉 Dialog close btn -->
    <DialogCloseBtn @click="$emit('update:isDialogVisible', false)" />

    <VCard class="pa-sm-10 pa-2">
      <h2 class="h2 text-center mb-6">Add Exchange Rate</h2>
      <VCardText>
        <!-- 👉 Form -->
        <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
          <VRow align="center" class="exchange-rate-container">
            <!-- Static text: "1 FORE =" -->


          </VRow>
          <VRow>
            <VCol cols="12" md="6">
              <AppDateTimePicker v-model="form.date" :rules="[
                requiredValidator,
                serverErrorValidator('date'),
              ]" label="Date " placeholder="Select date" />
            </VCol>


            <!-- 👉 Role -->
            <VCol cols="12" md="6">
              <AppSelect v-model="form.foreign_currency_id" label="Select Foreign Currency"
               @focus="fetchData"
                :rules="[requiredValidator, serverErrorValidator('foreign_currencies')]" :items="foreign_currencies"
                placeholder="Select Foreign Currency" />
            </VCol>


            <VCol cols="8" v-if="form.foreign_currency_id">
              <VRow class="d-flex align-center justify-center">
                <!-- Left Button: "1 FORE =" -->
                <VCol cols="3" class="d-flex align-center justify-end">
                  <VBtn variant="outlined" color="grey-darken-1" class="text-none">
                    1 {{ base_currency.code }}
                  </VBtn>
                </VCol>

                <!-- Exchange Rate Input -->
                <VCol cols="6">
                  <AppTextField v-model="form.exchange_rate" :rules="[
                    requiredValidator,
                    decimalValidator,
                    serverErrorValidator('Exchange Rate')
                  ]" placeholder="Exchange Rate" />
                </VCol>

                <!-- Right Static Text: "USD" -->
                <VCol cols="3" class="d-flex align-center">
                  <VBtn variant="outlined" color="grey-darken-1" class="text-none">
                    {{ selectedForeignCurrency }}
                  </VBtn>
                </VCol>
              </VRow>
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
