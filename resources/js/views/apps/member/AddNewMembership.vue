<script setup>
import AppSelect from "@/@core/components/app-form-elements/AppSelect.vue";
import { decimalValidator } from "@/@core/utils/validators";
import axiosAdmin from "@/composables/axios/axiosAdmin";
import { onMounted, ref, watch } from "vue";

// Form validation and ref
const isFormValid = ref(false);
const refForm = ref();
const membership_types = ref([]);
const membership_items = ref([]);
const searchQuery = ref();
const isSearchActive = ref();
const membership_type_id = ref();
const selected_membership_items = ref([]);
const trainers = ref([]);

const itemsPerPage = ref(10);
const page = ref(1);
const total = ref(0);

const headers = [
  { title: "Actions", key: "actions", sortable: false },
  { title: "Membership Name", key: "membership_name" },
  { title: "Membership Type", key: "membership_type.membership_type" },
  { title: "Validity", key: "validity" },
  { title: "Base Cost", key: "price" },
  { title: "Accessible Day", key: "accessible_days" },
  { title: "Sessions", key: "sessions" },
];

const form = ref({
  purchase_date: new Date(),
  selected_memberships: [],
});

// Watch for filter changes
watch([searchQuery, membership_type_id], () => {
  isSearchActive.value = true;
  fetchMembershipItem();
});

const fetchMembershipType = () => {
  axiosAdmin.get("/membership-types").then((response) => {
    membership_types.value = response.data.map((type) => ({
      title: type.membership_type,
      value: type.id,
    }));
  });
};

const fetchMembershipItem = () => {
  axiosAdmin
    .get("/membership-items", {
      params: {
        page: page.value,
        itemsPerPage: itemsPerPage.value,
        q: searchQuery.value,
        membership_type_id: membership_type_id.value,
      },
    })
    .then((response) => {
      membership_items.value = response.data;
      total.value = response.meta?.total;
    });
};

const fetchTrainer = () => {
  axiosAdmin
    .get("/trainers")
    .then((response) => {
      const categorized = response.data.reduce((acc, user) => {
        if (!acc[user.role]) {
          acc[user.role] = [];
        }
        // Push the user into the array for their role.
        acc[user.role].push(user);
        return acc;
      }, {});
      trainers.value = categorized;
    })
    .catch((error) => {
      console.log(error);
    });
};

const selectedMembershipType = (id) => {
  const selectedData = membership_items.value.find((item) => item.id === id);

  if (
    selectedData &&
    !selected_membership_items.value.some((item) => item.id === id)
  ) {
    const today = new Date();
    const validityDays = selectedData.validity;

    selected_membership_items.value.push({
      ...selectedData,
      discount_type: "$",
      discount: 0,
      discount_reason: "",
      valid_from:
        selectedData.membership_type?.membership_overlap === 1
          ? today.toISOString().split("T")[0]
          : "",
      valid_to:
        selectedData.membership_type?.membership_overlap === 1
          ? new Date(today.setDate(today.getDate() + validityDays - 1))
              .toISOString()
              .split("T")[0]
          : "",
    });
  }
};

const removeSelectedMembershipType = (id) => {
  selected_membership_items.value = selected_membership_items.value.filter(
    (item) => item.id !== id
  );
};

const netCost = (item) => {
  const base = parseFloat(item.price) || 0;
  const discount = parseFloat(item.discount) || 0;

  return item.discount_type === "%"
    ? (base - (base * discount) / 100).toFixed(2)
    : (base - discount).toFixed(2);
};

// Update form data whenever selected items change
watch(
  selected_membership_items,
  (items) => {
    console.log("hi");
    form.value.selected_memberships = items.map((item) => ({
      id: item.id,
      membership_name: item.membership_name,
      price: item.price,
      discount: item.discount,
      discount_type: item.discount_type,
      discount_reason: item.discount_reason,
      valid_from: item.valid_from,
      valid_to: item.valid_to,
    }));
  },
  { deep: true }
);

onMounted(() => {
  fetchMembershipType();
  fetchTrainer();
});
</script>

<template>
  <VCard flat>
    <VCardItem class="pb-4">
      <VCardTitle>Add New Membership</VCardTitle>
    </VCardItem>
    <VDivider />
    <VCardText>
      <VForm ref="refForm" v-model="isFormValid">
        <!-- Purchase Date Field -->
        <VRow>
          <VCol cols="12" md="6">
            <AppDateTimePicker
              v-model="form.purchase_date"
              :rules="[
                requiredValidator,
                serverErrorValidator('purchase_date'),
              ]"
              label="Purchase Date"
              placeholder="Select date"
            />
          </VCol>
        </VRow>

        <!-- Filter Section -->
        <VCardItem class="pb-4">
          <VCardTitle>Filter</VCardTitle>
        </VCardItem>

        <pre>{{ trainers }}</pre>
        <VRow>
          <VCol cols="12" md="6">
            <AppTextField
              v-model="searchQuery"
              prepend-inner-icon="tabler-search"
              label="Membership Name"
              placeholder="Search memberships"
            />
          </VCol>
          <VCol cols="12" md="6">
            <AppSelect
              v-model="membership_type_id"
              label="Membership Type"
              :items="membership_types"
              placeholder="Select type"
            />
          </VCol>
        </VRow>

        <!-- Membership Items Table -->
        <VDataTableServer
          v-if="isSearchActive"
          v-model:items-per-page="itemsPerPage"
          v-model:page="page"
          :items="membership_items"
          :headers="headers"
          :items-length="total"
          class="my-6"
        >
          <template #item.actions="{ item }">
            <VBtn @click="selectedMembershipType(item.id)">Add</VBtn>
          </template>
          <template #bottom>
            <TablePagination
              v-model:page="page"
              :items-per-page="itemsPerPage"
              :total-items="total"
            />
          </template>
        </VDataTableServer>

        <!-- Selected Memberships Table -->
        <VTable class="my-6">
          <thead>
            <tr>
              <th>Actions</th>
              <th>Membership Name</th>
              <th>Valid From</th>
              <th>Valid To</th>
              <th>Base Cost</th>
              <th>Discount Type</th>
              <th>Discount</th>
              <th>Reason</th>
              <th>Net Cost</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in selected_membership_items" :key="item.id">
              <td>
                <VBtn
                  @click="removeSelectedMembershipType(item.id)"
                  icon
                  variant="text"
                >
                  <VIcon icon="tabler-trash" />
                </VBtn>
              </td>
              <td>{{ item.membership_name }}</td>
              <td>{{ item.valid_from }}</td>
              <td>{{ item.valid_to }}</td>
              <td>{{ item.price }}</td>
              <td>
                <AppSelect v-model="item.discount_type" :items="['$', '%']" />
              </td>
              <td>
                <AppTextField
                  v-model="item.discount"
                  :rules="[decimalValidator]"
                />
              </td>
              <td>
                <AppTextField v-model="item.discount_reason" />
              </td>
              <td>{{ netCost(item) }}</td>
            </tr>
            <tr v-if="!selected_membership_items.length">
              <td colspan="9" class="text-center text-disabled">
                No memberships selected
              </td>
            </tr>
          </tbody>
        </VTable>

        <VRow>
          <VCol cols="12">
            <VBtn type="submit" class="me-3">Submit</VBtn>
          </VCol>
        </VRow>
      </VForm>
    </VCardText>
  </VCard>
</template>
