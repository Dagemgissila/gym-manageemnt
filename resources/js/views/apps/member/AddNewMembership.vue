<script setup>
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

const itemsPerPage = ref(10);
const page = ref(1);
const total = ref(0);

// 👉 Updated Headers for Membership Items Table
const headers = [
  { title: "Actions", key: "actions", sortable: false },
  { title: "Membership Name", key: "membership_name" },
  { title: "Membership Type", key: "membership_type.membership_type" }, // Foreign key reference
  { title: "validity", key: "duration_days" },
  { title: "Base Cost", key: "price" },
  { title: "Accessible Day", key: "accessible_days" },
  { title: "Sessions", key: "sessions" },
  { title: "Status", key: "status" },
];

watch([searchQuery, membership_type_id], (newVal) => {
  if (newVal) {
    isSearchActive.value = true;
    fetchMembershipItem();
  }
});

const form = ref({
  purchase_date: null,
  search_membership: "",
  membership_type: "",
});

const onSubmit = () => {
  clearAllServerErrors();

  refForm.value?.validate().then(({ valid }) => {
    if (valid) {
      axiosAdmin
        .post("/users", JSON.parse(JSON.stringify(form.value)))
        .then((response) => {
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

function fetchMembershipType() {
  axiosAdmin
    .get("/membership-types")
    .then((response) => {
      membership_types.value = response.data.map((membership_type) => ({
        title: membership_type.membership_type,
        value: membership_type.id,
      }));
    })
    .catch((error) => {});
}

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
      console.log(response);
      membership_items.value = response.data;
      total.value = response.meta?.total;
    })
    .catch((error) => {
      console.error("Error fetching users:", error);
    });
};

function selectedMembershipType(id) {
  const selectedData = membership_items.value.find(
    (membership_item) => membership_item.id == id
  );

  if (
    selectedData &&
    !selected_membership_items.value.some((item) => item.id == id)
  ) {
    selected_membership_items.value = [
      ...selected_membership_items.value,
      selectedData,
    ];
  }
}

function removeSelectedMembershipType(id) {
  selected_membership_items.value = selected_membership_items.value.filter(
    (membership_item) => membership_item.id != id
  );
}

onMounted(fetchMembershipType);
</script>

<template>
  <VCard flat>
    <VCardItem class="pb-4">
      <VCardTitle>Add New Membership</VCardTitle>
    </VCardItem>
    <VDivider />
    <VCardText>
      <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
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

        <!-- Ensure Filter section is outside the previous VRow -->
        <VCardItem class="pb-4">
          <VCardTitle>Filter</VCardTitle>
        </VCardItem>

        <VRow>
          <VCol cols="12" md="6">
            <AppTextField
              v-model="searchQuery"
              prepend-inner-icon="tabler-search"
              label="Membership Name"
              placeholder="Membership Name"
            />
          </VCol>

          <!-- 👉 Role -->
          <VCol cols="12" md="6">
            <AppSelect
              v-model="membership_type_id"
              label="Membership Type"
              :items="membership_types"
              placeholder="Select Membership Type"
            />
          </VCol>
        </VRow>

        <!-- SECTION datatable -->
        <VDataTableServer
          v-if="isSearchActive"
          v-model:items-per-page="itemsPerPage"
          v-model:page="page"
          :items="membership_items"
          item-value="id"
          :items-length="total"
          :headers="headers"
          class="text-no-wrap my-6"
        >
          <!-- Actions -->
          <template #item.actions="{ item }">
            <template v-if="item">
              <button @click="selectedMembershipType(item.id)">Add</button>
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

        <VCol cols="12">
          <VTable class="text-no-wrap">
            <thead>
              <tr>
                <th>Actions</th>
                <th>Membership Name</th>
                <th>valid From Date</th>
                <th>Valid to date</th>
                <th>Base Cost</th>
                <th>Discount</th>
                <th>Discount Reason</th>
                <th>Net Cost</th>
                <th>Trainner</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(item, index) in selected_membership_items"
                :key="item.id"
              >
                <td>
                  <button @click="removeSelectedMembershipType(item.id)">
                    Remove
                  </button>
                </td>
                <td>{{ item.membership_name }}</td>
                <td>
                  <VRow>
                    <VCol cols="12">
                      <AppDateTimePicker
                        v-model="form.purchase_date"
                        :rules="[
                          requiredValidator,
                          serverErrorValidator('purchase_date'),
                        ]"
                        placeholder="Select date"
                      />
                    </VCol>
                  </VRow>
                </td>
                <td>{{ item.valid_to }}</td>
                <td>{{ item.price }}</td>
                <td>
                  <AppTextField v-model="searchQuery" />
                </td>
                <td>
                  <AppTextField v-model="searchQuery" />
                </td>
                <td>
                  <AppTextField v-model="searchQuery" />
                </td>
                <td>{{ item.trainer }}</td>
              </tr>
              <tr v-if="!selected_membership_items.length > 0">
                <td colspan="5" class="text-center">
                  No day/time restrictions added
                </td>
              </tr>
            </tbody>
          </VTable>
        </VCol>

        <!-- Submit Button -->
        <VRow>
          <VCol cols="12">
            <VBtn type="submit" class="me-3">Submit</VBtn>
          </VCol>
        </VRow>
      </VForm>
    </VCardText>
  </VCard>
</template>
