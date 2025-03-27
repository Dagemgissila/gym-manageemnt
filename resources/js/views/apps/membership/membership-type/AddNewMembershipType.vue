<script setup>
import axiosAdmin from "@/composables/axios/axiosAdmin";
import { PerfectScrollbar } from "vue3-perfect-scrollbar";
import { toast } from "vue3-toastify";

const props = defineProps({
  isDrawerOpen: {
    type: Boolean,
    required: true,
  },
});

const emit = defineEmits(["update:isDrawerOpen", "membershipTypeData"]);

const isFormValid = ref(false);
const refForm = ref();
// Form fields
const membership_type = ref();
const membership_base = ref();
const background_color = ref("#ffffff"); // Default color
const isLiveMembership = ref(false);
const isMembershipOverlap = ref(false);
const status = ref(true);


const membership_bases = ref([
  { title: 'Duration Based', value: "Duration Based" },
  { title: 'Session Based', value: "Session Based" },
  { title: 'Classes Based', value: "Classes Based" },

])

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
        membership_type: membership_type.value,
        background_color: background_color.value,
        membership_base: membership_base.value,
        live_membership: isLiveMembership.value,
        membership_overlap: isMembershipOverlap.value,
        status: status.value
      };

      axiosAdmin
        .post("/membership-types", formatedMembershipTypeData)
        .then(function (response) {
          toast("Membership Type created successfully", {
            theme: "colored",
            type: "success",
            position: "top-right",
            dangerouslyHTMLString: true,
          });
          emit("membershipTypeData", {
            value: true,
          });
          emit("update:isDrawerOpen", false);
          nextTick(() => {
            refForm.value?.reset();
            refForm.value?.resetValidation();
            background_color.value = "#ffffff"; // Default color
            isLiveMembership.value = false;
            isMembershipOverlap.value = false;
            status.value = true;
          });
        })
        .catch((error) => {
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
</script>

<template>
  <VNavigationDrawer data-allow-mismatch temporary :width="500" location="end" class="scrollable-content"
    :model-value="props.isDrawerOpen" @update:model-value="handleDrawerModelValueUpdate">
    <!-- 👉 Title -->
    <AppDrawerHeaderSection title="Add Membership Type" @cancel="closeNavigationDrawer" />
    <VDivider />

    <PerfectScrollbar :options="{ wheelPropagation: false }">
      <VCard flat>
        <VCardText>
          <!-- 👉 Form -->
          <VForm ref="refForm" v-model="isFormValid" @submit.prevent="onSubmit">
            <VRow>
              <!-- 👉 Full name -->
              <VCol cols="12">
                <AppTextField v-model="membership_type"
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
                        <VColorPicker v-model="background_color" mode="hex" :modes="['hex']" />
                      </VListItem>
                    </VList>
                  </VMenu>
                  <!-- Display selected color in a rectangle -->
                  <div :style="{
                    width: '30px',
                    height: '30px',
                    backgroundColor: background_color,
                    border: '1px solid #ccc',
                    borderRadius: '4px',
                  }"></div>
                </div>
              </VCol>
              <VCol cols="12">
                <AppSelect v-model="membership_base" :items="membership_bases"
                  :rules="[serverErrorValidator('membership_base')]" item-title="title" item-value="value"
                  label="Membership Base" placeholder="Select a membership type" />
              </VCol>

              <VCol cols="12">
                <div class="demo-space-x">
                  <VSwitch v-model="isLiveMembership" :label="` Live Membership ?`" />
                </div>
              </VCol>

              <VCol cols="12">
                <div class="demo-space-x">
                  <VSwitch v-model="isMembershipOverlap" :label="` Membership Overlap ?`" />
                </div>
              </VCol>


              <VCol cols="12">
                <div class="demo-space-x">
                  <VSwitch v-model="status" :label="` Status`" />
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
