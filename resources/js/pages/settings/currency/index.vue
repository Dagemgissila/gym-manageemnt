<script setup>
import BaseCurrency from '@/views/apps/currency/BaseCurrency.vue'
import ExchangeRate from "@/views/apps/currency/ExchangeRate/index.vue"
import ForeignCurrency from "@/views/apps/currency/ForeignCurrency/index.vue"
const route = useRoute('apps-user-view-id')
const userTab = ref(null)

const tabs = [
  {
    icon: 'tabler-lock',
    title: 'Exchange Rates',
  },
  {
    icon: 'tabler-users',
    title: 'Base Currency',
  },
  {
    icon: 'tabler-bookmark',
    title: 'Foreign Currencies',
  }
]

const { data: userData } = await useApi(`/apps/users/${ route.params.id }`)
</script>

<template>
  <VRow v-if="userData">


    <VCol
      cols="12"
    >
      <VTabs
        v-model="userTab"
        class="v-tabs-pill"
      >
        <VTab
          v-for="tab in tabs"
          :key="tab.icon"
        >
          <VIcon
            :size="18"
            :icon="tab.icon"
            class="me-1"
          />
          <span>{{ tab.title }}</span>
        </VTab>
      </VTabs>

      <VWindow
        v-model="userTab"
        class="mt-6 disable-tab-transition"
        :touch="false"
      >

      <VWindowItem>
          <ExchangeRate />
        </VWindowItem>
        <VWindowItem>
          <BaseCurrency />
        </VWindowItem>


        <VWindowItem>
          <ForeignCurrency />
        </VWindowItem>


      </VWindow>
    </VCol>
  </VRow>

</template>
