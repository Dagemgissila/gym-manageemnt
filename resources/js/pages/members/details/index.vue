<script setup>
import axiosAdmin from "@/composables/axios/axiosAdmin";
import AddNewMembership from "@/views/apps/member/AddNewMembership.vue";
import Membership from "@/views/apps/member/Membership.vue";
import Voucher from "@/views/apps/voucher/index.vue";
import UserProfileHeader from "@/views/pages/user-profile/UserProfileHeader.vue";

import { onMounted, ref } from "vue";
import { useRoute } from "vue-router";

const route = useRoute();
const userTab = ref(0);
const member_id = route.params.id;
const member_detail = ref([]);
const requiredMembershipFields = ref([]);
const allFieldsFilled = ref(false);

const tabs = [
  { title: "Overview", icon: "tabler-home" },
  { title: "Membership Details", icon: "tabler-id" },
  { title: "Invoices & Receipts", icon: "tabler-calendar" },
];

const membership = [
  { title: "Buy New Membership", icon: "tabler-id", value: 9 },
];

// Using Tabler icons for the dropdown
const moreTabs = ref([
  { title: "Freeze", icon: "tabler-snowflake", value: "freeze" },
  { title: "Upgrade", icon: "tabler-arrow-up", value: "upgrade" },
  { title: "Transfer", icon: "tabler-arrows-swap", value: "transfer" },
  { title: "Refund", icon: "tabler-currency-dollar", value: "refund" },
  { title: "Gift Voucher", icon: "tabler-gift", value: "gift" },
  { title: "Referrals", icon: "tabler-users", value: "referrals" },
]);

const isAddCustomerDrawerOpen = ref(false);

const selectMoreTab = (index) => {
  userTab.value = index + tabs.length;
};

const fetchMemberDetail = async () => {
  const response = await axiosAdmin.get(`/members/${member_id}`);
  member_detail.value = response.data;
  await checkFieldValidation();
};

const checkFieldValidation = async () => {
  axiosAdmin
    .get("/field-validations")
    .then((response) => {
      requiredMembershipFields.value = response.filter(
        (field) => field.membership === "YES"
      );
      // Check if all required fields are filled
      allFieldsFilled.value = requiredMembershipFields.value.every((field) => {
        const value = member_detail.value[field.field_key];
        // Handle different field types
        if (field.is_multiple) {
          return Array.isArray(value) && value.length > 0;
        }
        if (field.input_type === "toggle") {
          return value !== null && value !== undefined;
        }
        return !!value;
      });
    })
    .catch((error) => {
      console.error("Error checking field validation:", error);
    });
};

function updateStatus() {
  allFieldsFilled.value = !allFieldsFilled.value;
}

onMounted(() => {
  fetchMemberDetail();
});
</script>

<template>
  <div>
    <!-- Header: Profile and Tab List Side by Side -->
    <div class="d-flex flex-column justify-space-between gap-4 mb-6">
      <!-- Customer Profile -->
      <div class="customer-profile">
        <UserProfileHeader
          @update-tab="selectMoreTab"
          :member_detail="member_detail"
        />
      </div>
      <!-- Tab List -->
      <div class="flex-grow-1 d-flex justify-space-between">
        <div class="d-flex">
          <VTabs
            v-model="userTab"
            class="v-tabs-pill disable-tab-transition"
            align-with-title
          >
            <VTab v-for="(tab, index) in tabs" :key="tab.title" :value="index">
              <VIcon size="20" start :icon="tab.icon" />
              {{ tab.title }}
            </VTab>
          </VTabs>

          <!-- More Dropdown -->
          <v-menu v-if="moreTabs.length" offset-y>
            <template v-slot:activator="{ props }">
              <v-btn
                class="align-self-center me-4"
                height="100%"
                rounded="0"
                variant="plain"
                v-bind="props"
              >
                Actions <v-icon icon="mdi-menu-down" end></v-icon>
              </v-btn>
            </template>
            <v-list class="bg-grey-lighten-3" style="z-index: 1000">
              <v-list-item
                v-for="(tab, index) in moreTabs"
                :key="index"
                @click="selectMoreTab(index)"
                class="cursor-pointer"
              >
                <v-icon size="20" start :icon="tab.icon" />
                {{ tab.title }}
              </v-list-item>
            </v-list>
          </v-menu>
        </div>

        <VTabs
          v-model="userTab"
          class="v-tabs-pill disable-tab-transition custom-green-tabs"
          align-with-title
        >
          <VTab
            v-for="(tab, index) in membership"
            :key="tab.title"
            @click="userTab = tab.value"
          >
            <VIcon size="20" :icon="tab.icon" />
            {{ tab.title }}
          </VTab>
        </VTabs>
      </div>
    </div>

    <!-- Tab Content Full Width Below -->
    <div class="tab-content">
      <VWindow v-model="userTab" class="disable-tab-transition" :touch="false">
        <VWindowItem :value="0"> this is tab value 0 </VWindowItem>
        <VWindowItem :value="1"> this is tab value 1 </VWindowItem>
        <VWindowItem :value="2"> this is another tabe </VWindowItem>

        <!-- add new membership  -->
        <VWindowItem :value="9">
          <Membership
            v-if="!allFieldsFilled"
            :member_detail="member_detail"
            @redirect-to-buy-membership="updateStatus"
            :required_fields="requiredMembershipFields"
          />
          <AddNewMembership v-else :member_detail="member_detail" />
        </VWindowItem>
        <!-- More Tab Contents -->
        <VWindowItem
          v-for="(tab, index) in moreTabs"
          :key="tab.title"
          :value="index + tabs.length"
        >
          <Voucher v-if="tab.value === 'gift'" />
          <div v-if="tab.value === 'freeze'">this is freeze page</div>
          <!-- Add more conditions as needed for other tab values -->
        </VWindowItem>
      </VWindow>
    </div>
  </div>
</template>

<style scoped>
.customer-profile {
  /* Customize layout if needed */
}

.tab-content {
  width: 100%;
}

.cursor-pointer {
  cursor: pointer;
}

.custom-green-tabs .v-tab {
  background-color: #4caf50 !important;
  /* Green Background */
}

.custom-green-tabs .v-tab {
  color: white !important;
  /* White text */
}
</style>
