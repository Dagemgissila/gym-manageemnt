<script setup>
import axiosAdmin from "@/composables/axios/axiosAdmin";
import common from "@/composables/common";
import AddPublicRule from "@/views/apps/public-variable/AddPublicRule.vue";
import EditPublicRule from "@/views/apps/public-variable/EditPublicRule.vue";
import { onMounted, ref } from "vue";


const {permsArray}=common();


// 👉 Data Table Options
const itemsPerPage = ref(10);
const page = ref(1);
const sortBy = ref("id");
const orderBy = ref("desc");
const selectedRows = ref([]);
const isAddPublicRuleVisible = ref(false);
const EditPublicRuleVisible = ref(false);
const selectedRule = ref({});
const searchQuery = ref();

// 👉 Users Data
const public_rules = ref([]);
const total = ref(0);

// 👉 Updated Headers for Membership Items Table
const headers = [
  { title: "Setting Rule", key: "setting_rule" }, // Foreign key reference
  { title: "Setting Value", key: "setting_value" },
  { title: "Status", key: "status" },
  { title: "Actions", key: "actions", sortable: false },
];

// 👉 Fetch Role
const fetchPublicRule = async () => {
  try {
    const response = await axiosAdmin.get("/public-rules", {
      params: {
        page: page.value,
        itemsPerPage: itemsPerPage.value,
        q: searchQuery.value,
      },
    });


    public_rules.value = response.data;
    total.value = response.meta.total;
  } catch (error) {
    console.error("Error fetching users:", error);
  }
};


const fetchPublicRuleById = async (id) => {
  try {
    const { data } = await axiosAdmin.get(`public-rules/${id}`);
    selectedRule.value = { // Corrected assignment syntax
      id: data.id,
      setting_rule: data.setting_rule,
      setting_value: data.setting_value,
      status: data.status
    };
    EditPublicRuleVisible.value=true
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
watch([page, itemsPerPage, sortBy, orderBy, searchQuery], fetchPublicRule);




onMounted(()=>{
  fetchPublicRule();
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
              placeholder="Search setting "
              style="inline-size: 200px"
              class="me-3"
            />
          </div>

          <!-- 👉 Add user button -->
          <VBtn
            prepend-icon="tabler-plus"
            @click="isAddPublicRuleVisible = true"
            v-if="permsArray.includes('public_rule_create') || permsArray.includes('admin')"
          >
            Add Public Rule
          </VBtn>
        </div>
      </VCardText>

      <VDivider />

      <!-- SECTION datatable -->
      <VDataTableServer
        v-model:items-per-page="itemsPerPage"
        v-model:model-value="selectedRows"
        v-model:page="page"
        :items="public_rules"
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
            <IconBtn v-if="permsArray.includes('public_rule_edit') || permsArray.includes('admin')"  @click="fetchPublicRuleById(item.id)">
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
    <AddPublicRule
      v-model:is-drawer-open="isAddPublicRuleVisible"
      @public-rule-data="fetchPublicRule"
    />

    <!-- Add User Drawer -->
    <EditPublicRule
      v-model:is-drawer-open="EditPublicRuleVisible"
      :selectedRule="selectedRule"
      @publicRuleData="fetchPublicRule"
    />
  </section>
</template>
