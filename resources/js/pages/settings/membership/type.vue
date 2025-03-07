<script setup>
import axiosAdmin from "@/composables/axios/axiosAdmin";
import common from "@/composables/common";
import AddNewMembershipType from "@/views/apps/membership/membership-type/AddNewMembershipType.vue";
import EditMembershipType from "@/views/apps/membership/membership-type/EditMembershipType.vue";
import { onMounted, ref } from "vue";

const {permsArray}=common();

// 👉 Data Table Options
const itemsPerPage = ref(10);
const page = ref(1);
const sortBy = ref("id");
const orderBy = ref("desc");
const selectedRows = ref([]);
const isAddNewMembershipTypeVisible = ref(false);
const EditMembershipTypeVisible = ref(false);
const selectedMembershipType = ref({});
const searchQuery = ref();

// 👉 Users Data
const membership_types = ref([]);
const total = ref(0);

// 👉 Headers
const headers = [
  { title: "Membership Type", key: "membership_type" },
  { title: "Membership Base", key: "membership_base" },
  { title: "Live Membership", key: "live_membership" },
  { title: "Background Color (Calendar)", key: "background_color" },
  { title: "Membership Overlap", key: "membership_overlap" },
  { title: "Status", key: "status" },

  { title: "Actions", key: "actions", sortable: true },
];

// 👉 Fetch Role
const fetchMembershipType = async () => {
  try {
    const response = await axiosAdmin.get("/membership-types", {
      params: {
        page: page.value,
        itemsPerPage: itemsPerPage.value,
        q: searchQuery.value,
      },
    });

    console.log(response.data)
    membership_types.value = response.data;
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
watch([page, itemsPerPage, sortBy, orderBy, searchQuery], fetchMembershipType);

// 👉 Initial fetch
onMounted(fetchMembershipType);



const fetchMembershipTypeById = async (membership_id) => {
  try {
    const { data } = await axiosAdmin.get(`/membership-types/${membership_id}`); // Fetch role by ID
    selectedMembershipType.value = {
      id: data.id,
      membership_type: data.membership_type,
      background_color: data.background_color,
      membership_base: data.membership_base,
      live_membership: data.live_membership,
      membership_overlap: data.membership_overlap,
      status:data.status
    };

    EditMembershipTypeVisible.value = true; // Open the drawer
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
              placeholder="Search Membership Type"
              style="inline-size: 220px"
              class="me-3"
            />
          </div>

          <!-- 👉 Add user button -->
          <VBtn
          v-if="permsArray.includes('membership_type_create') || permsArray.includes('admin')"
            prepend-icon="tabler-plus"
            @click="isAddNewMembershipTypeVisible = true"
          >
            Add Membership Type
          </VBtn>
        </div>
      </VCardText>

      <VDivider />

      <!-- SECTION datatable -->
      <VDataTableServer
        v-model:items-per-page="itemsPerPage"
        v-model:model-value="selectedRows"
        v-model:page="page"
        :items="membership_types"
        item-value="id"
        :items-length="total"
        :headers="headers"
        class="text-no-wrap"
        show-select
        @update:options="updateOptions"
      >
        <!-- Membership Type -->
        <template #item.membership_type="{ item }">
          <div class="text-sm ">{{ item.membership_type }}</div>
        </template>



        <!-- Live Membership -->
        <template #item.live_membership="{ item }">
          <div class="text-body-2">
            {{ item.live_membership ? "Yes" : "No" }}
          </div>
        </template>

                <!-- Live Membership -->
                <template #item.status="{ item }">
          <div class="text-body-2">
            {{ item.status ? "Active" : "Inactive" }}
          </div>
        </template>

        <!-- Background Color -->
        <template #item.background_color="{ item }">
          <!-- Display selected color in a rectangle -->
          <div
            :style="{
              width: '30px',
              height: '30px',
              backgroundColor: item.background_color,
              border: '1px solid #ccc',
              borderRadius: '4px',
            }"
          ></div>
        </template>

        <!-- Live Membership -->
        <template #item.membership_overlap="{ item }">
          <div class="text-body-2">
            {{ item.membership_overlap ? "Yes" : "No" }}
          </div>
        </template>

     <!-- Actions -->
     <template #item.actions="{ item }">
          <template v-if="item">
            <IconBtn v-if="permsArray.includes('membership_type_edit') || permsArray.includes('admin')" @click="fetchMembershipTypeById(item.id)">
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
    <AddNewMembershipType
      v-model:is-drawer-open="isAddNewMembershipTypeVisible"
      @membership-type-data="fetchMembershipType"
    />

    <!-- Add User Drawer -->
    <EditMembershipType
      v-model:is-drawer-open="EditMembershipTypeVisible"
      :selected-membership-type="selectedMembershipType"
      @update-membership-type-data="fetchMembershipType"
    />
  </section>
</template>
