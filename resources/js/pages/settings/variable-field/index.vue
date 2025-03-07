<script setup>
import axiosAdmin from "@/composables/axios/axiosAdmin";
import AddVariableField from "@/views/apps/variable-field/AddVariableField.vue";
import EditVariableField from "@/views/apps/variable-field/EditVariableField.vue";
import { onMounted, ref } from "vue";

// 👉 Data Table Options
const itemsPerPage = ref(10);
const page = ref(1);
const sortBy = ref("id");
const orderBy = ref("desc");
const selectedRows = ref([]);
const isAddVariableFieldVisible = ref(false);
const EditVariableFieldVisible = ref(false);
const selectedVariableField = ref({});
const searchQuery = ref();
const field_contents=ref();
const selectedField=ref();
const selectedStatus=ref();

// 👉 Users Data
const public_rules = ref([]);
const total = ref(0);

// 👉 Updated Headers for Membership Items Table
const headers = [
  { title: "Field", key: "field_content.name" }, // Foreign key reference
  { title: "Value", key: "value" },
  { title: "Status", key: "status" },
  { title: "Actions", key: "actions", sortable: false },
];

// 👉 Fetch Role
const fetchvariable = async () => {
  try {
    const response = await axiosAdmin.get("/variable-fields", {
      params: {
        page: page.value,
        itemsPerPage: itemsPerPage.value,
        q: searchQuery.value,
        field_content_id:selectedField.value,
        status:selectedStatus.value
      },
    });


    public_rules.value = response.data;
    total.value = response.meta.total;
  } catch (error) {
    console.error("Error fetching users:", error);
  }
};




// 👉 Fetch Role
const fetchFields = async () => {
  try {
    const response = await axiosAdmin.get("/field-contents",);


    field_contents.value = response.data.map((name) => ({
      title:  name.name,
      value: name.id,
    }));

  } catch (error) {
    console.error("Error fetching users:", error);
  }
};


const fetchvariableById = async (id) => {
  try {
    const { data } = await axiosAdmin.get(`/variable-fields/${id}`);

    console.log(data)
    selectedVariableField.value =data
    EditVariableFieldVisible.value=true
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
watch([page, itemsPerPage, sortBy, orderBy, searchQuery,selectedField,selectedStatus], fetchvariable);




onMounted(()=>{
  fetchvariable();
  fetchFields();
})


const status = [
  { title: "Active", value: 1 },
  { title: "Inactive", value: 0 },
];

</script>

<template>
  <section>
    <VCard class="mb-6">
      <VDivider />
{{}}
      <VCardText>
        <VRow>
          <!-- 👉 Select Role -->
          <VCol cols="12" sm="4">
            <AppSelect
              v-model="selectedField"
              placeholder="Select Field"
              :items="field_contents"
              clearable
              clear-icon="tabler-x"
            />
          </VCol>

          <!-- 👉 Select Status -->
          <VCol cols="12" sm="4">
            <AppSelect
              v-model="selectedStatus"
              placeholder="Select Status"
              :items="status"
              clearable
              clear-icon="tabler-x"
            />
          </VCol>
        </VRow>
      </VCardText>

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
            @click="isAddVariableFieldVisible = true"
          >
            Add variable Field
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
          <template v-if="item">
            <IconBtn @click="fetchvariableById(item.id)">
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
    <AddVariableField
      v-model:is-drawer-open="isAddVariableFieldVisible"
      @public-rule-data="fetchvariable"
    />

    <!-- Add User Drawer -->
    <EditVariableField
    v-if="EditVariableFieldVisible"
      v-model:is-drawer-open="EditVariableFieldVisible"
      :selectedVariableField="selectedVariableField"
      @publicRuleData="fetchvariable"
    />
  </section>
</template>
