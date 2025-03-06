<script setup>
import avatar1 from "@images/avatars/avatar-1.png";
import { PerfectScrollbar } from "vue3-perfect-scrollbar";

const accountData = {
  avatarImg: avatar1,
};

const props = defineProps({
  isDrawerOpen: {
    type: Boolean,
    required: true,
  },
  selectedUser: { type: Object, default: () => ({}) },
});

const accountDataLocal = ref(structuredClone(accountData));

const emit = defineEmits(["update:isDrawerOpen", "userData"]);

const handleDrawerModelValueUpdate = (val) => {
  emit("update:isDrawerOpen", val);
};

const closeNavigationDrawer = () => {
  emit("update:isDrawerOpen", false);
};

const avatarText = (name) => {
  if (!name) return "";
  const nameArray = name.split(" ");
  return nameArray
    .map((word) => word[0])
    .join("")
    .toUpperCase();
};

const resolveUserStatusVariant = (stat) => {
  if (!stat) return "primary"; // Fallback if status is undefined or null
  const stats = {
    active: "success",
    suspended: "error", // Use "error" for red color
    inactive: "secondary",
  };
  return stats[stat.toLowerCase()] || "primary";
};
</script>

<template>
  <VNavigationDrawer
    temporary
    :width="900"
    location="end"
    class="scrollable-content"
    :model-value="props.isDrawerOpen"
    @update:model-value="handleDrawerModelValueUpdate"
  >
    <!-- 👉 Title -->
    <AppDrawerHeaderSection
      title="User Detail"
      @cancel="closeNavigationDrawer"
    />

    <VDivider />

    <PerfectScrollbar :options="{ wheelPropagation: false }">
      <VCard flat>
        <!-- SECTION Customer Details -->
        <VCol cols="12">
          <VCard v-if="selectedUser">
            <VCardText class="text-center pt-12">
              <!-- 👉 Avatar -->
              <VAvatar
                rounded
                :size="120"
                :color="!selectedUser.profile_picture ? 'primary' : undefined"
                :variant="!selectedUser.profile_picture ? 'tonal' : undefined"
              >
                <VImg
                  v-if="selectedUser.profile_picture"
                  :src="selectedUser.profile_picture"
                />
                <span v-else class="text-5xl font-weight-medium">
                  {{
                    avatarText(
                      selectedUser.first_name + " " + selectedUser.last_name
                    )
                  }}
                </span>
              </VAvatar>

              <!-- 👉 Customer fullName -->
              <h5 class="text-h5 mt-4">
                {{ selectedUser.first_name + " " + selectedUser.last_name }}
              </h5>
              <div class="text-body-2">Role: {{ selectedUser.role }}</div>
            </VCardText>

            <!-- 👉 Customer Details -->
            <VCardText>
              <h5 class="text-h5">Details</h5>

              <VDivider class="my-4" />

              <VList class="card-list mt-2">
                <VListItem>
                  <h6 class="text-h6">
                    Email:
                    <span class="text-body-1 d-inline-block">
                      {{ selectedUser.email }}
                    </span>
                  </h6>
                </VListItem>

                <VListItem>
                  <h6 class="text-h6">
                    Mobile Number:
                    <span class="text-body-1 d-inline-block">
                      {{ selectedUser.mobile_number }}
                    </span>
                  </h6>
                </VListItem>

                <VListItem>
                  <h6 class="text-h6">
                    Gender:
                    <span class="text-body-1 d-inline-block">
                      {{ selectedUser.gender }}
                    </span>
                  </h6>
                </VListItem>

                <VListItem>
                  <h6 class="text-h6">
                    Date of Birth:
                    <span class="text-body-1 d-inline-block">
                      {{ selectedUser.date_of_birth }}
                    </span>
                  </h6>
                </VListItem>

                <VListItem>
                  <h6 class="text-h6">
                    Address:
                    <span class="text-body-1 d-inline-block">
                      {{ selectedUser.address }}
                    </span>
                  </h6>
                </VListItem>



                <VListItem>
                  <h6 class="text-h6">
                    Hire Date:
                    <span class="text-body-1 d-inline-block">
                      {{ selectedUser.hire_date }}
                    </span>
                  </h6>
                </VListItem>

                <VListItem>
                  <h6 class="text-h6">
                    Salary:
                    <span class="text-body-1 d-inline-block">
                      {{ selectedUser.salary }}
                    </span>
                  </h6>
                </VListItem>

                <VListItem>
                  <h6 class="text-h6">
                    Emergency Contact:
                    <span class="text-body-1 d-inline-block">
                      {{ selectedUser.emergency_contact_name }} ({{
                        selectedUser.emergency_contact_phone
                      }})
                    </span>
                  </h6>
                </VListItem>

                <VListItem>
                  <h6 class="text-h6">
                    Assigned Location:
                    <span class="text-body-1 d-inline-block">
                      {{ selectedUser.assigned_location }}
                    </span>
                  </h6>
                </VListItem>

                <VListItem>
                  <div class="d-flex gap-x-2 align-center">
                    <h6 class="text-h6">Status:</h6>
                    <VChip
                      label
                      :color="resolveUserStatusVariant(selectedUser.status)"
                      size="small"
                    >
                      {{ selectedUser.status }}
                    </VChip>
                  </div>
                </VListItem>
              </VList>
            </VCardText>
          </VCard>
        </VCol>
        <!-- !SECTION -->
      </VCard>
    </PerfectScrollbar>
  </VNavigationDrawer>
</template>
