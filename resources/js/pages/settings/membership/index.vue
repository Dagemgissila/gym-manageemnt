<script setup>
import axiosAdmin from "@/composables/axios/axiosAdmin";
import common from "@/composables/common";
import AddMembershipItem from "@/views/apps/membership/membership-item/AddMembershipItem.vue";
import EditmembershipItem from "@/views/apps/membership/membership-item/EditmembershipItem.vue";
import { onMounted, ref } from "vue";


const {permsArray}=common();

// 👉 Data Table Options
const itemsPerPage = ref(10);
const page = ref(1);
const sortBy = ref("id");
const orderBy = ref("desc");
const selectedRows = ref([]);
const isAddMembershipItemVisible = ref(false);
const EditmembershipItemVisible = ref(false);
const selectedMembershipItem = ref();
const searchQuery = ref();

// 👉 Users Data
const membership_items = ref([]);
const total = ref(0);

// 👉 Updated Headers for Membership Items Table
const headers = [
  { title: "Membership Name", key: "membership_name" },
  { title: "Membership Type", key: "membership_type.membership_type" }, // Foreign key reference
  { title: "Membership Duration (Days)", key: "duration_days" },
  { title: "Membership Price", key: "price" },
  { title: "Discount Available?", key: "discount_available" },
  { title: "Status", key: "status" },
  { title: "Actions", key: "actions", sortable: false },
];

// 👉 Fetch Role
const fetchMembershipItem = async () => {
  try {
    const response = await axiosAdmin.get("/membership-items", {
      params: {
        page: page.value,
        itemsPerPage: itemsPerPage.value,
        q: searchQuery.value,
      },
    });


    membership_items.value = response.data;
    total.value = response.meta.total;
  } catch (error) {
    console.error("Error fetching users:", error);
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
watch([page, itemsPerPage, sortBy, orderBy, searchQuery], fetchMembershipItem);

// 👉 Initial fetch
onMounted(fetchMembershipItem);



const fetchMembershipItemById = async (membership_id) => {
  try {
    const response= await axiosAdmin.get(`/membership-items/${membership_id}`); // Fetch role by ID
    const data=response.data;
    console.log(data);
    selectedMembershipItem.value = data;


    EditmembershipItemVisible.value = true; // Open the drawer
  } catch (error) {
    console.error("Error fetching role:", error);
  }
};



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
              placeholder="Search Membership Item"
              style="inline-size: 220px"
              class="me-3"
            />
          </div>

          <!-- 👉 Add user button -->
          <VBtn
          v-if="permsArray.includes('membership_item_create') || permsArray.includes('admin')"
            prepend-icon="tabler-plus"
            @click="isAddMembershipItemVisible = true"
          >
            Add Membership Item
          </VBtn>
        </div>
      </VCardText>

      <VDivider />

      <!-- SECTION datatable -->
      <VDataTableServer
        v-model:items-per-page="itemsPerPage"
        v-model:model-value="selectedRows"
        v-model:page="page"
        :items="membership_items"
        item-value="id"
        :items-length="total"
        :headers="headers"
        class="text-no-wrap"
        show-select
        @update:options="updateOptions"
      >
        <!-- Membership Type -->
        <template #item.installment_available="{ item }">
          <div class="text-body-2">
            {{ item.discount_available ? "Yes" : "No" }}
          </div>
        </template>

        <!-- Session-Based Membership -->
        <template #item.discount_available="{ item }">
          <div class="text-body-2">
            {{ item.discount_available ? "Yes" : "No" }}
          </div>
        </template>

                <!-- Session-Based Membership -->
                <template #item.status="{ item }">
          <div class="text-body-2">
            {{ item.status ? "Active" : "Inactive" }}
          </div>
        </template>

 

        <!-- Actions -->
        <template #item.actions="{ item }">
          <template v-if="item">

            <IconBtn  v-if="permsArray.includes('membership_item_edit') || permsArray.includes('admin')" @click="fetchMembershipItemById(item.id)">
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
    <AddMembershipItem
      v-model:is-drawer-open="isAddMembershipItemVisible"
      @membership-item-data="fetchMembershipItem"
    />

    <!-- Add User Drawer -->
    <EditmembershipItem
      v-model:is-drawer-open="EditmembershipItemVisible"
      :selected-membership-item="selectedMembershipItem"
      @membership-item-data="fetchMembershipItem"
    />
  </section>
</template>
