<script setup>
import axiosAdmin from "@/composables/axios/axiosAdmin";
import common from "@/composables/common";
import { computed, onMounted, ref } from "vue";
import { toast } from "vue3-toastify";

const {permsArray}=common();

// 👉 Data Table Options
const itemsPerPage = ref(10);
const page = ref(1);
const selectedRows = ref([]);
const field_validation_items = ref([]);

// 👉 Table Headers
const headers = [
  { title: "Field Name", key: "field_name" ,width: "400px" },
  { title: "Prospect", key: "prospect" },
  { title: "Trial", key: "trial" },
  { title: "Member", key: "membership" },
];

// 👉 Fetch Fields Data
const fetchFields = () => {
  axiosAdmin
    .get("/field-validations")
    .then((response) => {
      field_validation_items.value = response; // Ensure you access `.data`
    })
    .catch((error) => {
      console.error("Error fetching field validations:", error);
    });
};

// 👉 Group Data by `group` Column
const groupedFields = computed(() => {
  const groups = {};
  field_validation_items.value.forEach((item) => {
    if (!groups[item.group]) {
      groups[item.group] = [];
    }
    groups[item.group].push(item);
  });
  return groups;
});

// 👉 Handle Form Submission
const handleSubmit = () => {
  axiosAdmin
    .patch("/field-validations/bulk-update", field_validation_items.value)
    .then((response) => {
      toast("User created successfully", {
        theme: "colored",
        type: "success",
        position: "top-right",
        dangerouslyHTMLString: true,
      });
      fetchFields();
    })
    .catch((error) => {
      toast(error, {
        theme: "colored",
        type: "error",
        position: "top-right",
        dangerouslyHTMLString: true,
      });
    });
};

onMounted(() => {
  fetchFields();
});
</script>

<template>
  <section>
    <VCard class="mb-6">
      <VDivider />
      <VCardText class="d-flex flex-wrap gap-4">
        <div class="me-3 d-flex gap-3">
          <h1>Form Validation</h1>
        </div>
        <VSpacer />

        <div
          class="app-user-search-filter d-flex align-center flex-wrap gap-4"
        ></div>
      </VCardText>
      <VDivider />

      <!-- Grouped Data Table -->
      <div v-for="(items, group) in groupedFields" :key="group">
        <VCardItem class="pb-4">
        <VCardTitle>{{ group }}</VCardTitle>
      </VCardItem>
        <VDataTableServer
          v-model:model-value="selectedRows"
          v-model:page="page"
          :items="items"
          :headers="headers"
          :items-length="-1"
          class="text-no-wrap"
          :items-per-page="-1"
          hide-default-footer
          
        >
          <!-- Session-Based membershipship -->
          <template #item.prospect="{ item }">
            <div class="text-body-2">
              <VCol cols="12">
                <div class="demo-space-x">
                  <VSwitch
                    v-model="item.prospect"
                    true-value="YES"
                    false-value="NO"
                  />
                </div>
              </VCol>
            </div>
          </template>

          <!-- Live membershipship -->
          <template #item.trial="{ item }">
            <div class="text-body-2">
              <VCol cols="12">
                <div class="demo-space-x">
                  <VSwitch
                    v-model="item.trial"
                    true-value="YES"
                    false-value="NO"
                  />
                </div>
              </VCol>
            </div>
          </template>

          <!-- Live membershipship -->
          <template #item.membership="{ item }">
            <div class="text-body-2">
              <VCol cols="12">
                <div class="demo-space-x">
                  <VSwitch
                    v-model="item.membership"
                    true-value="YES"
                    false-value="NO"
                  />
                </div>
              </VCol>
            </div>
          </template>
        </VDataTableServer>
      </div>

      <!-- Submit Button -->
      <VCardText class="d-flex justify-center">
        <VBtn v-if="permsArray.includes('membership_item_edit') || permsArray.includes('admin')" color="primary" @click="handleSubmit"> Update </VBtn>
      </VCardText>
      <!-- SECTION -->
    </VCard>
  </section>
</template>
