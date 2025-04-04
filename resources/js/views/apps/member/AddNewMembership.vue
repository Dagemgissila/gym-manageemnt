<script setup>
import AppSelect from "@/@core/components/app-form-elements/AppSelect.vue";
import { discountValidator } from "@/@core/utils/validators";
import axiosAdmin from "@/composables/axios/axiosAdmin";
import { computed, onMounted, ref, watch } from "vue";
import { useRoute } from "vue-router";
import { toast } from "vue3-toastify";
// Form validation and ref
const isFormValid = ref(false);
const VisFormValid = ref(false);
const refForm = ref();
const VrefForm = ref();

const membership_types = ref([]);
const membership_items = ref([]);
const searchQuery = ref();
const isSearchActive = ref();
const membership_type_id = ref();
const selected_membership_items = ref([]);
const trainers = ref({});
const voucher_code = ref();
const member_voucher = ref(null);
const route = useRoute();
const member_id = route.params.id;

const itemsPerPage = ref(10);
const page = ref(1);
const total = ref(0);

const payment_methods = [
  { title: "Cash", value: "cash" },
  { title: "Wallet", value: "wallet" },
  { title: "Whish", value: "whish" },
];

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
  payment_method: [],
});

const selectedPayment = ref({
  type: "",
  amount: 0,
});

// Compute total paid amount
const totalPaidAmount = computed(() => {
  return form.value.payment_method.reduce(
    (sum, payment) => sum + Number(payment.amount),
    0
  );
});

// Compute remaining balance
const balance = computed(() => {
  return total_membership_amount.value - totalPaidAmount.value;
});

// Function to add a payment method
const addPayment = () => {
  if (!selectedPayment.value.type || selectedPayment.value.amount <= 0) {
    alert("Please select a payment method and enter a valid amount.");
    return;
  }

  if (selectedPayment.value.amount > balance.value) {
    alert(
      `Amount exceeds remaining balance! You can only pay up to ${balance.value}`
    );
    return;
  }

  // Add payment if within the limit
  form.value.payment_method.push({
    type: selectedPayment.value.type,
    amount: selectedPayment.value.amount,
  });

  // Reset input fields
  selectedPayment.value = { type: "", amount: 0 };
};

// Function to remove a payment
const removePayment = (index) => {
  form.value.payment_method.splice(index, 1);
};

const total_membership_amount = computed(() => {
  return form.value.selected_memberships
    .reduce((total, membership) => {
      const base = parseFloat(membership.price) || 0;
      const discount = parseFloat(membership.discount) || 0;
      let net = 0;
      if (membership.discount_type === "%") {
        net = base - (base * discount) / 100;
      } else {
        net = base - discount;
      }
      return total + net - (member_voucher.value?.amount || 0);
    }, 0)
    .toFixed(2);
});

// Prepare trainer items with headers and dividers for grouping
const groupedTrainerItems = computed(() => {
  const items = [];

  // Get all role names
  const roles = Object.keys(trainers.value);
  roles.forEach((role, index) => {
    if (trainers.value[role] && trainers.value[role].length) {
      // Add divider between groups (except before the first group)
      if (index > 0) {
        items.push({ type: "divider" });
      }
      items.push({
        type: "header",
        title: role.charAt(0).toUpperCase() + role.slice(1),
      });
      trainers.value[role].forEach((person) => {
        items.push({
          type: "item",
          title: `${person.first_name} ${person.last_name}`,
          value: person.id,
        });
      });
    }
  });

  return items;
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
      trainer_id: null,
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

const removeVoucher = () => {
  member_voucher.value = null;
};

const netCost = (item) => {
  const base = parseFloat(item.price) || 0;
  const discount = parseFloat(item.discount) || 0;

  return item.discount_type === "%"
    ? (base - (base * discount) / 100).toFixed(2)
    : (base - discount).toFixed(2);
};

// Get trainer name by ID
const getTrainerName = (id) => {
  if (!id) return "";

  // Search through all roles and trainers
  for (const role in trainers.value) {
    const found = trainers.value[role].find((person) => person.id === id);
    if (found) {
      return `${found.first_name} ${found.last_name}`;
    }
  }

  return "";
};

// Update form data whenever selected items change
watch(
  selected_membership_items,
  (items) => {
    form.value.selected_memberships = items.map((item) => ({
      id: item.id,
      membership_name: item.membership_name,
      price: item.price,
      discount: item.discount,
      discount_type: item.discount_type,
      discount_reason: item.discount_reason,
      trainer_id: item.trainer_id,
      valid_from: item.valid_from,
      valid_to: item.valid_to,
    }));
  },
  { deep: true }
);

const voucherCode = () => {
  clearAllServerErrors();

  VrefForm.value?.validate().then(({ valid }) => {
    if (valid) {
      axiosAdmin
        .get("/get-voucher", {
          params: {
            member_id: member_id,
            voucher_code: voucher_code.value,
          },
        })
        .then((response) => {
          console.log(response);
          member_voucher.value = response.data;
          nextTick(() => {
            VrefForm.value?.reset();
            VrefForm.value?.resetValidation();
          });
        })
        .catch((error) => {
          toast(error.data.voucher_error, {
            theme: "colored",
            type: "error",
            position: "top-right",
            dangerouslyHTMLString: true,
          });
          // Trigger re-validation to show server errors
          VrefForm.value?.validate();
        });
    }
  });
};

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
              <th>Trainer</th>
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
                  :rules="[discountValidator]"
                />
              </td>
              <td>
                <AppTextField
                  v-model="item.discount_reason"
                  class="discount-reason"
                />
              </td>
              <td>{{ netCost(item) }}</td>
              <td>
                <!-- Custom Trainer Selection with Manual Rendering -->
                <VMenu>
                  <template v-slot:activator="{ props }">
                    <VTextField
                      :model-value="getTrainerName(item.trainer_id)"
                      readonly
                      class="trainer-select"
                      v-bind="props"
                    />
                  </template>

                  <VList class="trainer-list">
                    <template
                      v-for="(groupItem, index) in groupedTrainerItems"
                      :key="index"
                    >
                      <!-- Render header -->
                      <VListSubheader v-if="groupItem.type === 'header'">
                        {{ groupItem.title }}
                      </VListSubheader>

                      <!-- Render divider -->
                      <VDivider v-else-if="groupItem.type === 'divider'" />

                      <!-- Render trainer item -->
                      <VListItem
                        v-else
                        :value="groupItem.value"
                        :title="groupItem.title"
                        @click="item.trainer_id = groupItem.value"
                      />
                    </template>
                  </VList>
                </VMenu>
              </td>
            </tr>

            <tr v-if="!selected_membership_items.length">
              <td colspan="10" class="text-center text-disabled">
                No memberships selected
              </td>
            </tr>
          </tbody>
        </VTable>

        <VRow class="d-flex flex-column align-end mt-6">
          <VCol cols="12" md="4">
            <VDivider />
            <VTable v-if="member_voucher != null">
              <thead>
                <tr>
                  <th>Voucher ID</th>
                  <th>Validity</th>
                  <th>Amount</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-if="member_voucher">
                  <td>{{ member_voucher.voucher_id }}</td>
                  <td>{{ member_voucher.validity }}</td>
                  <td>$ {{ member_voucher.amount }}</td>
                  <td>
                    <VBtn @click="removeVoucher()" icon>
                      <VIcon icon="tabler-trash" />
                    </VBtn>
                  </td>
                </tr>
              </tbody>
            </VTable>
          </VCol>
          <VCol cols="12" md="4">
            <h3 class="text-h5 text-center mt-4">Voucher Code</h3>

            <VCardText>
              <VForm
                ref="VrefForm"
                v-model="VisFormValid"
                @submit.prevent="voucherCode"
              >
                <VRow>
                  <VCol cols="12">
                    <AppTextField
                      v-model="voucher_code"
                      :rules="[requiredValidator]"
                      label="Enter Voucher Code"
                      placeholder="Enter Code"
                      class="text-lg"
                    />
                  </VCol>

                  <VCol cols="12" class="text-right">
                    <VBtn type="submit" class="px-6 py-3">Apply</VBtn>
                  </VCol>
                </VRow>
              </VForm>
            </VCardText>
          </VCol>
        </VRow>

        <VRow class="d-flex justify-end mt-6">
          <!-- Total Membership Amount Section -->
          <VCol cols="4" class="text-right">
            <h3 class="text-h5 font-weight-bold mb-4">
              Total Membership Amount : {{ total_membership_amount }}
            </h3>
          </VCol>
        </VRow>

        <VCard>
          <VTable class="my-6">
            <thead>
              <tr>
                <th>Payment Method</th>
                <th>Amount</th>
                <th>Balance</th>
                <th>Net Payable Amount</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <VCol cols="12" md="6">
                    <AppSelect
                      v-model="selectedPayment.type"
                      :items="payment_methods"
                      placeholder="Select type"
                    />
                  </VCol>
                </td>
                <td>
                  <AppTextField
                    v-model="selectedPayment.amount"
                    type="number"
                    placeholder="Enter amount"
                  />
                </td>
                <td>{{ balance }}</td>
                <td>{{ total_membership_amount }}</td>
              </tr>
            </tbody>
          </VTable>

          <!-- Add Payment Button -->
          <VBtn @click="addPayment"> Add Payment </VBtn>

          <!-- Display Added Payments -->
          <VTable class="mt-4" v-if="form.payment_method.length">
            <thead>
              <tr>
                <th>actions</th>
                <th>Payment Method</th>
                <th>Amount</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(payment, index) in form.payment_method" :key="index">
                <td>
                  <VBtn @click="removePayment(index)" icon variant="text">
                    <VIcon icon="tabler-trash" />
                  </VBtn>
                </td>
                <td>{{ payment.type }}</td>
                <td>{{ payment.amount }}</td>
              </tr>
            </tbody>
          </VTable>
        </VCard>
      </VForm>
    </VCardText>
  </VCard>
</template>

<style scoped>
.trainer-select,
.discount-reason {
  min-width: 200px;
}

.trainer-list {
  max-height: 350px;
  overflow-y: auto;
}
</style>
