<script setup>
import axiosAdmin from "@/composables/axios/axiosAdmin";
import common from "@/composables/common";
import Create from "@/views/apps/voucher/create.vue";
import { onMounted, ref } from "vue";
import { useRoute } from "vue-router";

const props = defineProps({
  isActive: Boolean,
});

const { permsArray } = common();

// 👉 Data Table Options
const itemsPerPage = ref(10);
const page = ref(1);
const sortBy = ref("id");
const orderBy = ref("desc");
const selectedRows = ref([]);
const isCreateDialogVisible = ref(false);
const route = useRoute();
const member_id=route.params.id;
// 👉 Users Data
const member_vouchers = ref([]);
const total = ref(0);

// 👉 Updated Headers for Membership Items Table
const headers = [
  { title: "Voucher Number", key: "voucher_id" }, // Foreign key reference
  { title: "Amount ($)", key: "amount" },
  { title: "Validity", key: "validity" },
  { title: "Comments", key: "comment" },

  { title: "Actions", key: "actions", sortable: false },
];

// 👉 Fetch Role
const fetchMemberVoucher = async () => {
  try {
    const response = await axiosAdmin.get("/vouchers", {
      params: {
        member_id: member_id,
      },
    });
    member_vouchers.value = response.data;
  } catch (error) {
    console.error("Error fetching vouchers:", error);
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
watch([page, itemsPerPage, sortBy, orderBy], fetchMemberVoucher);

onMounted(() => {
  fetchMemberVoucher();
});


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
          <!-- 👉 Add user button -->
          <VBtn
            prepend-icon="tabler-plus"
            @click="isCreateDialogVisible = true"
            v-if="
              permsArray.includes('public_rule_create') ||
              permsArray.includes('admin')
            "
          >
            Create Voucher
          </VBtn>
        </div>
      </VCardText>

      <VDivider />

      <!-- SECTION datatable -->
      <VDataTableServer
        v-model:items-per-page="itemsPerPage"
        v-model:model-value="selectedRows"
        v-model:page="page"
        :items="member_vouchers"
        item-value="id"
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
            <IconBtn
              v-if="
                permsArray.includes('public_rule_edit') ||
                permsArray.includes('admin')
              "
              @click="fetchForeignCurrencyById(item.id)"
            >
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
      @fetch-member-voucher="fetchMemberVoucher"
    />
  </section>
</template>
