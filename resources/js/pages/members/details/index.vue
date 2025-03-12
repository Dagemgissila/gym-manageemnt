<script setup>
import ECommerceAddCustomerDrawer from '@/views/apps/ecommerce/ECommerceAddCustomerDrawer.vue'
import CustomerTabAddressAndBilling from '@/views/apps/ecommerce/customer/view/CustomerTabAddressAndBilling.vue'
import CustomerTabNotification from '@/views/apps/ecommerce/customer/view/CustomerTabNotification.vue'
import CustomerTabOverview from '@/views/apps/ecommerce/customer/view/CustomerTabOverview.vue'
import CustomerTabSecurity from '@/views/apps/ecommerce/customer/view/CustomerTabSecurity.vue'
import UserProfileHeader from "@/views/pages/user-profile/UserProfileHeader.vue"

import { ref } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()
const customerData = ref(null)
const userTab = ref(0)

const tabs = [
  { title: 'Overview', icon: 'tabler-home' },
  { title: 'Billing', icon: 'tabler-credit-card' },
  { title: 'Membership Details', icon: 'tabler-id' },
  { title: 'Appointments', icon: 'tabler-calendar' },
  { title: 'Communications', icon: 'tabler-message' },
  { title: 'Needs Attention', icon: 'tabler-alert-triangle' },
  { title: 'Related Tasks', icon: 'tabler-list-check' },
  { title: 'Attendance', icon: 'tabler-check' },
  { title: 'Workout & Diet', icon: 'tabler-dumbbell' },
  { title: 'Upgrade', icon: 'tabler-arrow-up' },
  { title: 'Transfer', icon: 'tabler-repeat' },
  { title: 'Freeze', icon: 'tabler-snowflake' },
  { title: 'Digital Wallet', icon: 'tabler-wallet' },
  { title: 'Refferals', icon: 'tabler-users' },
  { title: 'Loyalty Points', icon: 'tabler-star' },
]

const { data } = await useApi(`/apps/ecommerce/customers/${route.params.id}`)
if (data.value) {
  customerData.value = data.value
}

const isAddCustomerDrawerOpen = ref(false)
</script>

<template>
  <div>
    <!-- Header: Profile and Tab List Side by Side -->
    <div class="d-flex flex-column  justify-space-between gap-4 mb-6">
      <!-- Customer Profile -->
      <div class="customer-profile">
        <UserProfileHeader/>

      </div>
      <!-- Tab List -->
      <div class="flex-grow-1">
        <VTabs v-model="userTab" class="v-tabs-pill disable-tab-transition" align-with-title>
          <VTab v-for="(tab, index) in tabs" :key="tab.title" :value="index">
            <VIcon size="20" start :icon="tab.icon" />
            {{ tab.title }}
          </VTab>
        </VTabs>
      </div>

      

      
    </div>

    <!-- Tab Content Full Width Below -->
    <div class="tab-content">
      <VWindow v-model="userTab" class="disable-tab-transition" :touch="false">
        <VWindowItem :value="0">
          <CustomerTabOverview />
        </VWindowItem>
        <VWindowItem :value="1">
          <CustomerTabSecurity />
        </VWindowItem>
        <VWindowItem :value="2">
          <CustomerTabAddressAndBilling />
        </VWindowItem>
        <VWindowItem :value="3">
          <CustomerTabNotification />
        </VWindowItem>
      </VWindow>
    </div>

    <ECommerceAddCustomerDrawer v-model:is-drawer-open="isAddCustomerDrawerOpen" />
  </div>
</template>

<style scoped>
/* Adjust layout if needed */
.customer-profile {
  /* You can customize the width or margins here */
}
.tab-content {
  width: 100%;
}
</style>
