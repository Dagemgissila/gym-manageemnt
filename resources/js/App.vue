<script setup>
import ScrollToTop from '@core/components/ScrollToTop.vue'
import initCore from '@core/initCore'
import {
  initConfigStore,
  useConfigStore,
} from '@core/stores/config'
import { hexToRgb } from '@core/utils/colorConverter'
import { computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useTheme } from 'vuetify'
import { useStore } from 'vuex/dist/vuex.cjs.js'
const { global } = useTheme()

// ℹ️ Sync current theme with initial loader theme
initCore()
initConfigStore()

const router = useRouter()
const store = useStore()


const isLoggedIn = computed(() => store.getters["auth/isLoggedIn"]);

onMounted(() => {
  if (router.currentRoute && router.currentRoute.value) {
    setInterval(() => {
      if (isLoggedIn) {
        store.dispatch("auth/refreshToken")
      }
    }, 20 * 60 * 1000) // Refresh token every 20 minutes
  }
})


const configStore = useConfigStore()
</script>

<template>
  <VLocaleProvider :rtl="configStore.isAppRTL">
    <!-- ℹ️ This is required to set the background color of active nav link based on currently active global theme's primary -->
    <VApp :style="`--v-global-theme-primary: ${hexToRgb(global.current.value.colors.primary)}`">
      <RouterView />
      <ScrollToTop />
    </VApp>
  </VLocaleProvider>
</template>
