<script setup>
import axiosAdmin from "@/composables/axios/axiosAdmin";
import common from "@/composables/common";
import { onMounted, ref } from "vue";
import Create from "./create.vue";
import Edit from "./Edit.vue";


const { permsArray } = common();


// 👉 Data Table Options
const itemsPerPage = ref(10);
const page = ref(1);
const sortBy = ref("id");
const orderBy = ref("desc");
const selectedRows = ref([]);
const isCreateDialogVisible = ref(false);
const isEditDialogVisible = ref(false);
const selectedExchangeRate = ref({});
const searchQuery = ref();

// 👉 Users Data
const exchange_rate = ref([]);
const total = ref(0);

// 👉 Updated Headers for Membership Items Table
const headers = [
  { title: "Currency", key: "foreign_currency" }, // Foreign key reference
  { title: "Rate", key: "exchange_rate" },
  { title: "Date", key: "date" },
  { title: "Actions", key: "actions", sortable: false },
];

// 👉 Fetch Role
const fetchExchangeRate = async () => {
  try {
    const response = await axiosAdmin.get("/exchange-rates", {
      params: {
        page: page.value,
        itemsPerPage: itemsPerPage.value,
        q: searchQuery.value,
      },
    });

    exchange_rate.value = response.data;
    total.value = response.meta.total;
  } catch (error) {
    console.error("Error fetching users:", error);
  }
};


const fetchExchangeRateById = async (id) => {
  try {
    const { data } = await axiosAdmin.get(`exchange-rates/${id}`);
    selectedExchangeRate.value = { // Corrected assignment syntax
      id: data.id,
      date: data.date,
      base_currency_id: data.base_currency.id,
      foreign_currency_id: data.foreign_currency.id, // Fixed here
      exchange_rate: data.exchange_rate
    };
    isEditDialogVisible.value = true
  } catch (error) {
    console.error("Error fetching public rule:", error);
  }
};


// 👉 Update sorting options
const updateOptions = (options) => {
  if (options.sortBy.length > 0) {
    sortBy.value = options.sortBy[0].key;
    orderBy.value = options.sortBy[0].order;
  } else {
    sortBy.value = "id";
    orderBy.value = "desc";
  }
};

// 👉 Watch for changes that require data refresh
watch([page, itemsPerPage, sortBy, orderBy, searchQuery], fetchExchangeRate);


onMounted(() => {
  fetchExchangeRate();
})

</script>

<template>
  <section>
    <VCard class="mb-6">
      <VDivider />

      <VCardText class="d-flex flex-wrap gap-4">
        <div class="me-3 d-flex gap-3">
          <AppSelect :model-value="itemsPerPage" :items="[
            { value: 10, title: '10' },
            { value: 25, title: '25' },
            { value: 50, title: '50' },
            { value: 100, title: '100' },
            { value: -1, title: 'All' },
          ]" style="inline-size: 6.25rem" @update:model-value="itemsPerPage = parseInt($event, 10)" />
        </div>
        <VSpacer />

        <div class="app-user-search-filter d-flex align-center flex-wrap gap-4">
          <div class="d-flex align-center">
            <!-- 👉 Search  -->
            <AppTextField v-model="searchQuery" placeholder="Search Exchange Rate " style="inline-size: 200px"
              class="me-3" />
          </div>

          <!-- 👉 Add user button -->
          <VBtn prepend-icon="tabler-plus" @click="isCreateDialogVisible = true"
            v-if="permsArray.includes('public_rule_create') || permsArray.includes('admin')">
            Add Exchange Rate
          </VBtn>
        </div>
      </VCardText>

      <VDivider />

      <!-- SECTION datatable -->
      <VDataTableServer v-model:items-per-page="itemsPerPage" v-model:model-value="selectedRows" v-model:page="page"
        :items="exchange_rate" item-value="id" :items-length="total" :headers="headers" class="text-no-wrap" show-select
        @update:options="updateOptions">

        <!-- Exchnage Rate -->
        <template #item.foreign_currency="{ item }">
          <div class="text-body-2">
            {{ ` ${item.foreign_currency.code} - ${item.foreign_currency.name}` }}
          </div>
        </template>
        <!-- Exchnage Rate -->
        <template #item.exchange_rate="{ item }">
          <div class="text-body-2">
            {{ `1 ${item.base_currency.code} = ${item.exchange_rate} ${item.foreign_currency.code}` }}
          </div>
        </template>



        <!-- Actions -->
        <template #item.actions="{ item }">
          <template v-if="item">
            <IconBtn v-if="permsArray.includes('public_rule_edit') || permsArray.includes('admin')"
              @click="fetchExchangeRateById(item.id)">
              <VIcon icon="tabler-pencil" />
            </IconBtn>
          </template>
        </template>

        <!-- pagination -->
        <template #bottom>
          <TablePagination v-model:page="page" :items-per-page="itemsPerPage" :total-items="total" />
        </template>
      </VDataTableServer>
      <!-- SECTION -->
    </VCard>

    <Create 
    v-model:is-dialog-visible="isCreateDialogVisible"
     @foreign-exchange-rate="fetchExchangeRate" />

    <!-- Add User Drawer -->
    <Edit
      v-model:is-dialog-visible="isEditDialogVisible" 
      :selectedExchangeRate="selectedExchangeRate"
      @foreign-exchange-rate="fetchExchangeRate" />
  </section>
</template>
