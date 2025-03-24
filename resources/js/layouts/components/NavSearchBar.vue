<script setup>
import axiosAdmin from "@/composables/axios/axiosAdmin";
import Shepherd from "shepherd.js";
const isLoading = ref(false);
const isSearchActive = ref(false);
const searchQuery = ref("");
const router = useRouter();
const users = ref([]);
const totalUsers = ref(0);

// 👇 Fetch Users
const fetchUsers = async () => {
  try {
    isLoading.value = true;
    const { data, meta } = await axiosAdmin.get("/members", {
      params: {
        q: searchQuery.value,
      },
    });

    users.value = data;
    totalUsers.value = meta.total;
  } catch (error) {
    console.error("Error fetching users:", error);
  } finally {
    isLoading.value = false;
  }
};

watch(searchQuery, (newVal) => {
  if (newVal) {
    isSearchActive.value = true;
    fetchUsers();
  }
});


// // Add immediate option to watch to handle initial state
// watch(searchQuery, fetchUsers, { immediate: true });

const clearSearch = () => {
  searchQuery.value = "";
  users.value = [];
};

const redirectToUserProfile = (user) => {
  router.push({ name: "user-profile", params: { id: user.id } });
  isSearchActive.value = false;
};
</script>

<template>
  <div class="d-flex align-center justify-center w-100">
    <VMenu
      v-model="isSearchActive"
      :close-on-content-click="false"
      location="bottom"
      offset-y
      :max-width="600"
      class="mx-auto"
    >
      <template #activator="{ props }">
        <VTextField
          v-model="searchQuery"
          v-bind="props"
          placeholder="Search members..."
          prepend-inner-icon="tabler-search"
          clearable
          solo
          dense
          hide-details
          single-line
          class="no-border"
          @click:clear="clearSearch"
          @click="Shepherd.activeTour?.cancel()"
        />
      </template>

      <VCard v-if="isSearchActive" class="search-results-dropdown" @click.stop>
        <VCardText>
          <div v-if="searchQuery">
            <div v-if="isLoading" class="text-center py-4">
              <VProgressCircular indeterminate />
            </div>

            <template v-else>
              <div v-if="users.length > 0">
                <VList>
                  <RouterLink
                    v-for="user in users"
                    :key="user.id"
                    @click="isSearchActive = false"
                  >
                    <div class="d-flex align-center gap-x-3">
                      <VAvatar
                        size="36"
                        :variant="!user.avatar ? 'tonal' : undefined"
                      >
                        <VImg
                          v-if="user.profile_picture"
                          :src="user.profile_picture"
                        />
                        <span v-else>{{
                          avatarText(user.first_name + " " + user.last_name)
                        }}</span>
                      </VAvatar>
                      <div class="d-flex flex-column">
                        <RouterLink
                          to=""
                          class="text-link font-weight-medium d-inline-block"
                          style="line-height: 1.375rem"
                        >
                          {{ user.first_name + " " + user.last_name }}
                        </RouterLink>
                        <div class="text-body-2">
                          {{ user.email }}
                        </div>
                      </div>
                    </div>
                  </RouterLink>
                </VList>

              </div>

    
              <div
              v-else
                class="d-flex flex-column flex-md-row gap-2 justify-center py-4"
              >
                <RouterLink :to="{ name: 'member-create-prospect' }">
                  <VBtn
                    @click="isSearchActive = false"
                    color="primary"
                    class="w-100 w-md-auto"
                  >
                    Create Prospect
                  </VBtn>
                </RouterLink>

                <RouterLink :to="{ name: 'member-create-trial;' }">
                  <VBtn
                    @click="isSearchActive = false"
                    color="primary"
                    class="w-100 w-md-auto"
                  >
                    Book Trial
                  </VBtn>
                </RouterLink>

                <RouterLink :to="{ name: 'member-create-membership' }">
                  <VBtn
                    @click="isSearchActive = false"
                    color="primary"
                    class="w-100 w-md-auto"
                  >
                    Buy New Membership
                  </VBtn>
                </RouterLink>
              </div>
            </template>
          </div>
        </VCardText>
      </VCard>
    </VMenu>
  </div>
</template>

<style lang="scss">
.no-border .v-text-field__control {
  border: none !important;
  box-shadow: none !important;
}

.search-results-dropdown {
  width: 100%;
  margin-top: 4px;
  max-height: 60vh;
  overflow-y: auto;
  overflow-x: auto;

  .v-card-text {
    padding: 0.5rem !important;
  }

  .v-list-item {
    min-height: 64px;
    cursor: pointer;
    transition: none;

    &:hover {
      background-color: rgba(var(--v-theme-primary), 0.04);
    }
  }
}

.v-menu__content {
  overflow: visible;
  contain: none;
}

.v-field__prepend-inner {
  padding-inline-start: 12px !important;
}
</style>
