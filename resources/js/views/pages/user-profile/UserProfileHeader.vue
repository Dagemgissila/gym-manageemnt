<script setup>
const profileHeaderData = ref();
const { data, error } = await useApi("/pages/profile/header");
console.log("ff", error.value);
if (error.value) {
  console.log(error.value);
} else {
  if (data.value) profileHeaderData.value = data.value;
}
</script>

<template>

  <VCard >


    <VCardText
      class="d-flex align-bottom flex-sm-row flex-column justify-center gap-x-6"
    >
      <div class="d-flex h-0 mt-10 mb-10">
        <VAvatar
          rounded
          size="130"
          image="http://127.0.0.1:5174/resources/images/avatars/avatar-1.png"

          class="user-profile-avatar mx-auto m"
        />
      </div>

      <div class="user-profile-info w-100 mt-16 pt-6 pt-sm-0 mt-sm-0">
        <h4 class="text-h4 text-center text-sm-start font-weight-medium mb-2">
        dagem
        </h4>

        <div
          class="d-flex align-center justify-center justify-sm-space-between flex-wrap gap-5"
        >
          <div
            class="d-flex flex-wrap justify-center justify-sm-start flex-grow-1 gap-6"
          >
            <span class="d-flex gap-x-2 align-center">
              <VIcon size="24" icon="tabler-palette" />
              <div class="text-body-1 font-weight-medium">
                UX Designer

              </div>
            </span>

            <span class="d-flex gap-x-2 align-center">
              <VIcon size="24" icon="tabler-map-pin" />
              <div class="text-body-1 font-weight-medium">
                Addis Ababa
              </div>
            </span>

            <span class="d-flex gap-x-2 align-center">
              <VIcon size="24" icon="tabler-calendar" />
              <div class="text-body-1 font-weight-medium">
                today
              </div>
            </span>
          </div>

          <VBtn prepend-icon="tabler-user-check"> Connected </VBtn>
        </div>
      </div>
    </VCardText>
  </VCard>
</template>

<style lang="scss">
.user-profile-avatar {
  border: 5px solid rgb(var(--v-theme-surface));
  background-color: rgb(var(--v-theme-surface)) !important;
  inset-block-start: -3rem;

  .v-img__img {
    border-radius: 0.125rem;
  }
}
</style>
