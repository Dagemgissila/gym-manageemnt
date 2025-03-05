<script setup>
import axiosAdmin from "@/composables/axios/axiosAdmin";
import { onMounted, ref } from "vue";
import { toast } from "vue3-toastify";

// 👉 Data Table Options
const itemsPerPage = ref(10);
const page = ref(1);

const selectedRows = ref([]);

// 👉 Users Data
const field_validation_items = ref([]);
const total = ref(0);

// 👉 Updated Headers for Membership Items Table
const headers = [
  { title: "Field Name", key: "field_name" }, // Foreign key reference
  { title: "Prospect", key: "prospect" },
  { title: "Trial", key: "trial" },
  { title: "Member", key: "member" },
];


function fetchFilds(){
  axiosAdmin
    .get("/field-validations")
    .then((response) => {
      field_validation_items.value = response; // Make sure to access `.data`
    })
    .catch((error) => {
      console.error("Error fetching field validations:", error);
    });
}

onMounted(() => {
  fetchFilds();
});


// Handle form submission
const handleSubmit = () => {
  console.log("Form submitted:", field_validation_items.value);

  // Send the updated data to the backend
  axiosAdmin
    .patch("/field-validations/bulk-update", field_validation_items.value)
    .then((response) => {

      toast(response.message, {
      theme: "colored",
      type: "success",
      position: "top-right",
      dangerouslyHTMLString: true,
    });
  fetchFilds();

      console.log("Update successful:", response);
    })
    .catch((error) => {
      console.error("Error updating field validations:", error);
    });
};


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

      <!-- SECTION datatable -->
      <VDataTableServer
        v-model:model-value="selectedRows"
        v-model:page="page"
        :items="field_validation_items"
        :headers="headers"
        :items-length="-1"
        class="text-no-wrap"
        :items-per-page="-1"
        hide-default-footer
      >
        <!-- Session-Based Membership -->
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

        <!-- Live Membership -->
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

        <!-- Live Membership -->
        <template #item.member="{ item }">
          <div class="text-body-2">
            <VCol cols="12">
              <div class="demo-space-x">
                <VSwitch
                  v-model="item.member"
                  true-value="YES"
                  false-value="NO"
                />
              </div>
            </VCol>
          </div>
        </template>
      </VDataTableServer>
      <!-- SECTION -->


            <!-- Submit Button -->
            <VCardText class="d-flex justify-center">
        <VBtn color="primary" @click="handleSubmit"> Submit </VBtn>
      </VCardText>
      <!-- SECTION -->
    </VCard>
  </section>
</template>
