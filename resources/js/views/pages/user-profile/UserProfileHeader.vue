<script setup>
const profileHeaderData = ref();
const { data, error } = await useApi("/pages/profile/header");
if (error.value) {
  console.log(error.value);
} else {
  if (data.value) profileHeaderData.value = data.value;
}


defineProps({
  member_detail:{
    type:Object,
    required:true
  }
})
const emit = defineEmits(["update-tab"]);

// Map each clickable field to a tab index (adjust these as needed)
const goToTab = (tabIndex) => {
  emit("update-tab", tabIndex);
};

const resolveStatus = (statusMsg) => {
  if (statusMsg === "Scheduled")
    return {
      text: "Scheduled",
      color: "warning",
    };
  if (statusMsg === "Member")
    return {
      text: "Member",
      color: "success",
    };
  if (statusMsg === "prospect")
    return {
      text: "Prospect",
      color: "error",
    };
};
</script>

<template>
  <VCard>
    <VCardText
      class="d-flex align-center flex-sm-row flex-column justify-center gap-x-6"
    >
      <div class="d-flex p-4">
        <VAvatar
          rounded
          size="130"
          image="https://plus.unsplash.com/premium_photo-1689568126014-06fea9d5d341?fm=jpg&q=60&w=3000&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8cHJvZmlsZXxlbnwwfHwwfHx8MA%3D%3D"
          class="user-profile-avatar mx-auto m"
        />
      </div>

      <div class="user-profile-info w-100 mt-16 pt-6 pt-sm-0 mt-sm-0">
        <div class="mb-2">
          <h4 class="text-h5 text-center text-sm-start font-weight-medium mb-2">
            {{ member_detail.first_name + " " + member_detail.last_name }}
          </h4>

          <VChip
            v-bind="resolveStatus(member_detail.status)"
            density="default"
            label
            size="small"
          />
        </div>
        <div
          class="d-flex align-center justify-center justify-sm-space-between flex-wrap gap-5"
        >
          <div
            class="d-flex flex-wrap justify-center justify-sm-start flex-grow-1 gap-6"
          >
            <span
              class="d-flex gap-x-2 align-center cursor-pointer"
              @click="goToTab(0)"
            >
              <VIcon size="24" icon="tabler-clock" />
              <div class="text-body-1 font-weight-medium">
                2025-03-15 04:35 PM
              </div>
            </span>

            <span
              class="d-flex gap-x-2 align-center cursor-pointer"
              @click="goToTab(1)"
            >
              <VIcon size="24" icon="tabler-calendar-event" />
              <div class="text-body-1 font-weight-medium">3/12</div>
            </span>

            <span
              class="d-flex gap-x-2 align-center cursor-pointer"
              @click="goToTab(2)"
            >
              <VIcon size="24" icon="tabler-wallet" />
              <div class="text-body-1 font-weight-medium">$25</div>
            </span>

            <span
              class="d-flex gap-x-2 align-center cursor-pointer"
              @click="goToTab(3)"
            >
              <VIcon size="24" icon="tabler-star" />
              <div class="text-body-1 font-weight-medium">65</div>
            </span>
          </div>

          <div class="notification">
            <VIcon icon="tabler-bell" />
          </div>
        </div>
      </div>
    </VCardText>
  </VCard>
</template>

<style lang="scss">
.user-profile-avatar {
  border: 5px solid rgb(var(--v-theme-surface));
  background-color: rgb(var(--v-theme-surface)) !important;

  .v-img__img {
    border-radius: 0.125rem;
  }
}
.notification {
  display: flex;
  align-items: center;
  gap: 5px; /* Adjust spacing between icon and text */
  font-size: 16px; /* Adjust text size */
  color: #333; /* Adjust text color */
}
</style>
