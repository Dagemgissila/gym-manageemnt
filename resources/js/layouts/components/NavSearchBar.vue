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
    const { data, meta } = await axiosAdmin.get("/users", {
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

watch(searchQuery, fetchUsers);

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
      transition="scale-transition"
      offset-y
      :max-width="600"
      class="mx-auto"
    >
      <template #activator="{ props }">
        <VTextField
          v-model="searchQuery"
          v-bind="props"
          placeholder="Search users..."
          prepend-inner-icon="tabler-search"
          clearable
          solo
          dense
          hide-details
          single-line
          style="min-width: 400px"
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
                  <VListItem
                    v-for="user in users"
                    :key="user.id"
                    @click="redirectToUserProfile(user)"
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
                          {{ user.first_name  + ' ' + user.last_name}}
                        </RouterLink>
                        <div class="text-body-2">
                          {{ user.email }}
                        </div>
                      </div>
                    </div>

             
                  </VListItem>
                </VList>

                <div
                  v-if="totalUsers > users.length"
                  class="text-caption text-disabled text-center mt-2"
                >
                  Showing {{ users.length }} of {{ totalUsers }} results
                </div>
              </div>

              <!-- If no users found, display suggestion buttons -->
              <div v-else class="text-center py-4">
                <VBtn
                  color="primary"
                  class="mx-2"
                  @click="redirectToSuggestion('create-prospect')"
                >
                  Create Prospect
                </VBtn>
                <VBtn
                  color="primary"
                  class="mx-2"
                  @click="redirectToSuggestion('book-trial')"
                >
                  Book Trial
                </VBtn>
                <VBtn
                  color="primary"
                  class="mx-2"
                  @click="redirectToSuggestion('buy-new-membership')"
                >
                  Buy New Membership
                </VBtn>
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

  .v-card-text {
    padding: 0.5rem !important;
  }

  .v-list-item {
    min-height: 64px;
    cursor: pointer;
    transition: background-color 0.2s ease;

    &:hover {
      background-color: rgba(var(--v-theme-primary), 0.04);
    }
  }
}

.v-menu__content {
  overflow: visible;
  contain: none;
  transform-origin: center top;
}

.v-field__prepend-inner {
  padding-inline-start: 12px !important;
}
</style>
