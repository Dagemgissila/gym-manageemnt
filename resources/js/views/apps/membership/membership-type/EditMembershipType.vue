<script setup>
import axiosAdmin from "@/composables/axios/axiosAdmin";
import { PerfectScrollbar } from "vue3-perfect-scrollbar";
import { toast } from "vue3-toastify";

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
  clearAllServerErrors();
  refForm.value?.validate().then(({ valid }) => {
    if (valid) {
      const formatedMembershipTypeData = {
        id: props.selectedMembershipType.id ?? null,
        membership_type: props.selectedMembershipType.membership_type,
        background_color: props.selectedMembershipType.background_color,
        membership_base: props.selectedMembershipType.membership_base,
        live_membership: props.selectedMembershipType.live_membership,
        membership_overlap: props.selectedMembershipType.membership_overlap,
        status: props.selectedMembershipType.status
      };

      axiosAdmin
        .patch(
          `/membership-types/${formatedMembershipTypeData.id}`,
          formatedMembershipTypeData
        ) // Include the ID in the URL
        .then(function (response) {
          toast("Membership Type updated successfully", {
            theme: "colored",
            type: "success",
            position: "top-right",
            dangerouslyHTMLString: true,
          });
          emit("updateMembershipTypeData", {
            value: true,
          });

          emit("update:isDrawerOpen", false);
          nextTick(() => {
            refForm.value?.reset();
            refForm.value?.resetValidation();

          });
        })
        .catch(function (error) {
          handleServerErrors(error);
          // Trigger re-validation to show server errors
          refForm.value?.validate();
        });
    }
  });
};

const handleDrawerModelValueUpdate = (val) => {
  emit("update:isDrawerOpen", val);
};


const membership_bases = ref([
  { title: 'Duration Based', value: "Duration Based" },
  { title: 'Session Based', value: "Session Based" },
  { title: 'Classes Based', value: "Classes Based" },

])


</script>

<template>
  <VNavigationDrawer data-allow-mismatch temporary :width="500" location="end" class="scrollable-content"
    :model-value="props.isDrawerOpen" @update:model-value="handleDrawerModelValueUpdate">
    <!-- 👉 Title -->
    <AppDrawerHeaderSection title="Edit Membership Type" @cancel="closeNavigationDrawer" />

    <VDivider />

    <PerfectScrollbar :options="{ wheelPropagation: false }">
      <VCard flat>
        <VCardText>
          <!-- 👉 Form -->
          <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
            <VRow>
              <!-- 👉 Full name -->
              <VCol cols="12">
                <AppTextField v-model="selectedMembershipType.membership_type"
                  :rules="[requiredValidator, serverErrorValidator('membership_type')]" label="Membership Type"
                  placeholder="Gym Membership" />
              </VCol>
              <!-- 👉 Background Color Picker -->
              <VCol cols="12">
                <VLabel>Background Color (Calendar)</VLabel>
                <div class="d-flex align-center gap-3 mt-2">
                  <VBtn icon size="30" variant="flat" style="border-radius: 0.375rem">
                    <VIcon size="20" icon="tabler-color-picker" />
                  </VBtn>
                  <VMenu activator="parent" :close-on-content-click="false">
                    <VList>
                      <VListItem>
                        <VColorPicker v-model="selectedMembershipType.background_color" mode="hex" :modes="['hex']" />
                      </VListItem>
                    </VList>
                  </VMenu>
                  <!-- Display selected color in a rectangle -->
                  <div :style="{
                    width: '30px',
                    height: '30px',
                    backgroundColor: selectedMembershipType.background_color,
                    border: '1px solid #ccc',
                    borderRadius: '4px',
                  }"></div>
                </div>
              </VCol>

              <VCol cols="12">
                <AppSelect v-model="selectedMembershipType.membership_base" :items="membership_bases"
                  :rules="[serverErrorValidator('membership_base')]" item-title="title" item-value="value"
                  label="Membership Base" placeholder="Select a membership type" />
              </VCol>

              <VCol cols="12">
                <div class="demo-space-x">
                  <VSwitch v-model="selectedMembershipType.live_membership" :label="` Live Membership ?`"
                    :true-value="1" :false-value="0" />
                </div>
              </VCol>

              <VCol cols="12">
                <div class="demo-space-x">
                  <VSwitch v-model="selectedMembershipType.membership_overlap" :label="` Membership Overlap ?`"
                    :true-value="1" :false-value="0" />
                </div>
              </VCol>


              <VCol cols="12">
                <div class="demo-space-x">
                  <VSwitch v-model="selectedMembershipType.status" :label="`Status`" :true-value="1" :false-value="0" />
                </div>
              </VCol>

              <!-- 👉 Submit and Cancel -->
              <VCol cols="12">
                <VBtn type="submit" class="me-3"> Submit </VBtn>
                <VBtn type="reset" variant="tonal" color="error" @click="closeNavigationDrawer">
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
