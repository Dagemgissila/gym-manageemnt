<script setup>
import common from '@/composables/common'
import { layoutConfig } from '@layouts'
import { useLayoutConfigStore } from '@layouts/stores/config'
import { getDynamicI18nProps } from '@layouts/utils'

const props = defineProps({
  item: {
    type: null,
    required: true,
  },
})

const {permsArray}=common();

const configStore = useLayoutConfigStore()
const shallRenderIcon = configStore.isVerticalNavMini()
</script>

<template>
  
  <li
  v-if="!item.permission || permsArray.includes(item.permission) || permsArray.includes('admin')"

    class="nav-section-title"
  >
    <div class="title-wrapper">
      <Transition
        name="vertical-nav-section-title"
        mode="out-in"
      >
        <Component
          :is="shallRenderIcon ? layoutConfig.app.iconRenderer : layoutConfig.app.i18n.enable ? 'i18n-t' : 'span'"
          :key="shallRenderIcon"
          :class="shallRenderIcon ? 'placeholder-icon' : 'title-text'"
          v-bind="{ ...layoutConfig.icons.sectionTitlePlaceholder, ...getDynamicI18nProps(item.heading, 'span') }"
        >
          {{ !shallRenderIcon ? item.heading : null }}
        </Component>
      </Transition>
    </div>
  </li>
</template>
