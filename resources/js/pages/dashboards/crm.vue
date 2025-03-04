<script setup>
import LogisticsCardStatistics from '@/views/apps/logistics/LogisticsCardStatistics.vue';
import { computed } from 'vue';
import { useStore } from 'vuex/dist/vuex.cjs.js';
definePage({
  meta:{
    requireAuth:true,
  }
})
const store = useStore();
const user = computed(() => store.state.auth.user);
const isLogged=computed(()=>store.getters["auth/isLoggedIn"])
</script>

<template>
  <VRow class="match-height">

    <VCol cols="12"
    >
      <LogisticsCardStatistics />
    </VCol>
  


  </VRow>
</template>
