<script setup>
import axiosAdmin from "@/composables/axios/axiosAdmin";
import common from "@/composables/common";
import Create from "@/views/apps/currency/ForeignCurrency/create.vue";
import { onMounted, ref } from "vue";
import Edit from "./Edit.vue";


const {permsArray}=common();


// 👉 Data Table Options
const itemsPerPage = ref(10);
const page = ref(1);
const sortBy = ref("id");
const orderBy = ref("desc");
const selectedRows = ref([]);
const isCreateDialogVisible = ref(false);
const isEditDialogVisible = ref(false);
const selectedForeignCurrency = ref({});
const searchQuery = ref();

// 👉 Users Data
const foreign_currencies = ref([]);
const total = ref(0);

// 👉 Updated Headers for Membership Items Table
const headers = [
  { title: "Code", key: "code" }, // Foreign key reference
  { title: "Name", key: "name" },
  { title: "Symbol", key: "symbol" },
  { title: "Decimal Places", key: "decimal_place" },

  { title: "Actions", key: "actions", sortable: false },
];

// 👉 Fetch Role
const fetchForeignCurrency = async () => {
  try {
    const response = await axiosAdmin.get("/foreign-currencies", {
      params: {
        page: page.value,
        itemsPerPage: itemsPerPage.value,
        q: searchQuery.value,
      },
    });


    foreign_currencies.value = response.data;
    total.value = response.meta.total;
  } catch (error) {
    console.error("Error fetching users:", error);
  }
};


const fetchForeignCurrencyById = async (id) => {
  try {
    const { data } = await axiosAdmin.get(`foreign-currencies/${id}`);
    selectedForeignCurrency.value = { // Corrected assignment syntax
      id: data.id,
      code: data.code,
      name: data.name,
      symbol: data.symbol,
      decimal_place: data.decimal_place
    };
    isEditDialogVisible.value=true
  } catch (error) {
    console.error("Error fetching foreign currency:", error);
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
watch([page, itemsPerPage, sortBy, orderBy, searchQuery], fetchForeignCurrency);


onMounted(()=>{
  fetchForeignCurrency();
})

</script>

<template>
  <section>
    <VCard class="mb-6">
      <VDivider />

      <VCardText class="d-flex flex-wrap gap-4">
        <div class="me-3 d-flex gap-3">
          <AppSelect
            :model-value="itemsPerPage"
            :items="[
              { value: 10, title: '10' },
              { value: 25, title: '25' },
              { value: 50, title: '50' },
              { value: 100, title: '100' },
              { value: -1, title: 'All' },
            ]"
            style="inline-size: 6.25rem"
            @update:model-value="itemsPerPage = parseInt($event, 10)"
          />
        </div>
        <VSpacer />

        <div class="app-user-search-filter d-flex align-center flex-wrap gap-4">
          <div class="d-flex align-center">
            <!-- 👉 Search  -->
            <AppTextField
              v-model="searchQuery"
              placeholder="Search Foreign Currency "
              style="inline-size: 200px"
              class="me-3"
            />
          </div>

          <!-- 👉 Add user button -->
          <VBtn
            prepend-icon="tabler-plus"
            @click="isCreateDialogVisible = true"
            v-if="permsArray.includes('public_rule_create') || permsArray.includes('admin')"
          >
            Add Foreign Currency
          </VBtn>
        </div>
      </VCardText>

      <VDivider />

      <!-- SECTION datatable -->
      <VDataTableServer
        v-model:items-per-page="itemsPerPage"
        v-model:model-value="selectedRows"
        v-model:page="page"
        :items="foreign_currencies"
        item-value="id"
        :items-length="total"
        :headers="headers"
        class="text-no-wrap"
        show-select
        @update:options="updateOptions"
      >
        <!-- Status -->
        <template #item.status="{ item }">
          <div class="text-body-2">
            {{ item.status ? "Active" : "Inactive" }}
          </div>
        </template>


        <!-- Actions -->
        <template #item.actions="{ item }">
          <template v-if="item" >
            <IconBtn v-if="permsArray.includes('public_rule_edit') || permsArray.includes('admin')"  @click="fetchForeignCurrencyById(item.id)">
              <VIcon icon="tabler-pencil" />
            </IconBtn>
          </template>
        </template>

        <!-- pagination -->
        <template #bottom>
          <TablePagination
            v-model:page="page"
            :items-per-page="itemsPerPage"
            :total-items="total"
          />
        </template>
      </VDataTableServer>
      <!-- SECTION -->
    </VCard>
    <!-- Add User Drawer -->
    <Create
      v-model:is-dialog-visible="isCreateDialogVisible"
      @foreign-currency="fetchForeignCurrency"
    />

    <!-- Add User Drawer -->
    <Edit
      v-model:is-dialog-visible="isEditDialogVisible"
      :selectedForeignCurrency="selectedForeignCurrency"
      @foreign-currency="fetchForeignCurrency"
    />
  </section>
</template>
