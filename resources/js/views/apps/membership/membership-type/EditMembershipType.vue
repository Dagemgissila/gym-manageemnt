<script setup>
import { PerfectScrollbar } from "vue3-perfect-scrollbar";

const props = defineProps({
  isDrawerOpen: {
    type: Boolean,
    required: true,
  },
  selectedMembershipType: { type: Object, default: () => ({}) },
});

const emit = defineEmits(["update:isDrawerOpen", "updateMembershipTypeData"]);

const isFormValid = ref(false);
const refForm = ref();
// Form fields



// 👉 drawer close
const closeNavigationDrawer = () => {
  emit("update:isDrawerOpen", false);
  nextTick(() => {
    refForm.value?.reset();
    refForm.value?.resetValidation();
  });
};

const onSubmit = () => {
  refForm.value?.validate().then(({ valid }) => {
    if (valid) {
      emit("updateMembershipTypeData", {
        id: props.selectedMembershipType.id ?? null,
        membershipType: props.selectedMembershipType.membership_type,
        background_color: props.selectedMembershipType.background_color,
        isSessionBased: props.selectedMembershipType.is_session_based,
        isLiveMembership: props.selectedMembershipType.live_membership,
        isMembershipOverlap: props.selectedMembershipType.membership_overlap,
      });
      
      emit("update:isDrawerOpen", false);
      nextTick(() => {
        refForm.value?.reset();
        refForm.value?.resetValidation();
        isSessionBased.value = false;
        isLiveMembership.value = false;
        isMembershipOverlap.value = false;
      });
    }
  });
};

const handleDrawerModelValueUpdate = (val) => {
  emit("update:isDrawerOpen", val);
};
</script>

<template>
  <VNavigationDrawer
    data-allow-mismatch
    temporary
    :width="500"
    location="end"
    class="scrollable-content"
    :model-value="props.isDrawerOpen"
    @update:model-value="handleDrawerModelValueUpdate"
  >
    <!-- 👉 Title -->
    <AppDrawerHeaderSection
      title="Edit Membership Type"
      @cancel="closeNavigationDrawer"
    />

    <VDivider />

    <PerfectScrollbar :options="{ wheelPropagation: false }">
      <VCard flat>
        <VCardText>
          <!-- 👉 Form -->
          <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
            <VRow>
              <!-- 👉 Full name -->
              <VCol cols="12">
                <AppTextField
                  v-model="selectedMembershipType.membership_type"
                  :rules="[requiredValidator]"
                  label="Membership Type"
                  placeholder="Gym Membership"
                />
              </VCol>
              <!-- 👉 Background Color Picker -->
              <VCol cols="12">
                <VLabel>Background Color (Calendar)</VLabel>
                <div class="d-flex align-center gap-3 mt-2">
                  <VBtn
                    icon
                    size="30"
                    variant="flat"
                    style="border-radius: 0.375rem"
                  >
                    <VIcon size="20" icon="tabler-color-picker" />
                  </VBtn>
                  <VMenu activator="parent" :close-on-content-click="false">
                    <VList>
                      <VListItem>
                        <VColorPicker
                          v-model="selectedMembershipType.background_color"
                          mode="hex"
                          :modes="['hex']"
                        />
                      </VListItem>
                    </VList>
                  </VMenu>
                  <!-- Display selected color in a rectangle -->
                  <div
                    :style="{
                      width: '30px',
                      height: '30px',
                      backgroundColor: selectedMembershipType.background_color,
                      border: '1px solid #ccc',
                      borderRadius: '4px',
                    }"
                  ></div>
                </div>
              </VCol>

              <VCol cols="12">
                <div class="demo-space-x">
                  <VSwitch
                    v-model="selectedMembershipType.is_session_based"
                    :label="` Session-Based Membership ?`"
                    :true-value="1"
                    :false-value="0"
                  />
                </div>
              </VCol>

              <VCol cols="12">
                <div class="demo-space-x">
                  <VSwitch
                    v-model="selectedMembershipType.live_membership"
                    :label="` Live Membership ?`"
                    :true-value="1"
                    :false-value="0"
                  />
                </div>
              </VCol>

              <VCol cols="12">
                <div class="demo-space-x">
                  <VSwitch
                    v-model="selectedMembershipType.membership_overlap"
                    :label="` Membership Overlap ?`"
                    :true-value="1"
                    :false-value="0"
                  />
                </div>
              </VCol>

              <!-- 👉 Submit and Cancel -->
              <VCol cols="12">
                <VBtn type="submit" class="me-3"> Submit </VBtn>
                <VBtn
                  type="reset"
                  variant="tonal"
                  color="error"
                  @click="closeNavigationDrawer"
                >
                  Cancel
                </VBtn>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </PerfectScrollbar>
  </VNavigationDrawer>
</template>
