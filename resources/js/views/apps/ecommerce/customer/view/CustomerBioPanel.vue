<script setup>
const props = defineProps({
  customerData: {
    type: null,
    required: true,
  },
});

const isUserInfoEditDialogVisible = ref(false);
const isUpgradePlanDialogVisible = ref(false);

const customerData = {
  id: props.customerData.id,
  fullName: props.customerData.customer,
  firstName: "dagi",
  lastName: "dagi",
  company: "",
  role: "",
  username: props.customerData.customer,
  country: props.customerData.country,
  contact: props.customerData.contact,
  email: props.customerData.email,
  currentPlan: "",
  status: props.customerData.status,
  avatar: "",
  taskDone: null,
  projectDone: null,
  taxId: "Tax-8894",
  language: "English",
};
</script>

<template>
  <VRow>
    <!-- SECTION Customer Details -->
    <VCol cols="12">
      <VCard v-if="props.customerData">
        <VCardText class="text-center pt-12">
          <!-- 👉 Avatar -->
          <VAvatar
            rounded
            :size="120"
            :color="!props.customerData.customer ? 'primary' : undefined"
            :variant="!props.customerData.avatar ? 'tonal' : undefined"
          >
            <VImg
              v-if="props.customerData.avatar"
              :src="props.customerData.avatar"
            />
            <span v-else class="text-5xl font-weight-medium">
              {{ avatarText(props.customerData.customer) }}
            </span>
          </VAvatar>

          <!-- 👉 Customer fullName -->
          <h5 class="text-h5 mt-4">
            {{ props.customerData.customer }}
          </h5>
        </VCardText>

        <!-- 👉 Customer Details -->
        <VCardText>
          <h5 class="text-h5">Details</h5>

          <VDivider class="my-4" />

          <VList class="card-list mt-2">
            <VListItem>
              <h6 class="text-h6">
                Fullname:
                <span class="text-body-1 d-inline-block">
                  {{ props.customerData.customer }}
                </span>
              </h6>
            </VListItem>
          </VList>

          <VDivider class="my-4" />

          <VList class="card-list mt-2">
            <VListItem>
              <h6 class="text-h6">
                Phone:
                <span class="text-body-1 d-inline-block">
                  {{ props.customerData.customer }}
                </span>
              </h6>
            </VListItem>
          </VList>
        </VCardText>
      </VCard>
    </VCol>
    <!-- !SECTION -->
  </VRow>
  <UserInfoEditDialog
    v-model:is-dialog-visible="isUserInfoEditDialogVisible"
    :user-data="customerData"
  />
  <UserUpgradePlanDialog
    v-model:is-dialog-visible="isUpgradePlanDialogVisible"
  />
</template>

<style lang="scss" scoped>
.card-list {
  --v-card-list-gap: 0.5rem;
}

.current-plan {
  background: linear-gradient(
    45deg,
    rgb(var(--v-theme-primary)) 0%,
    #9e95f5 100%
  );
  color: #fff;
}
</style>
