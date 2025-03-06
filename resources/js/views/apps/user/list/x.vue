<script setup>
import axiosAdmin from "@/composables/axios/axiosAdmin";
import avatar1 from "@images/avatars/avatar-1.png";
import { onMounted } from "vue";
import { PerfectScrollbar } from "vue3-perfect-scrollbar";

const accountData = {
  avatarImg: avatar1,
};

const props = defineProps({
  isDrawerOpen: {
    type: Boolean,
    required: true,
  },
});

// Dynamic server errors object
const serverErrors = ref({});

// Server error validator
const serverErrorValidator = (fieldName) => () => {
  return serverErrors.value[fieldName] || true;
};

const accountDataLocal = ref(structuredClone(accountData));

const emit = defineEmits(["update:isDrawerOpen", "userData"]);

const isFormValid = ref(false);
const refForm = ref();
const roles = ref([]);

// 👉 Form fields
const form = ref({
  first_name: "",
  last_name: "",
  email: "",
  mobile_number: "",
  date_of_birth: "",
  profile_picture: "",
  address: "",
  assigned_location: "",
  hire_date: "",
  salary: "",
  emergency_contact_name: "",
  emergency_contact_phone: "",
  role: "",
  status: "",
  gender: "",
  profile_picture: null,
});

const refInputEl = ref();

const changeAvatar = (file) => {
  const fileReader = new FileReader();
  const { files } = file.target;
  if (files && files.length) {
    fileReader.readAsDataURL(files[0]);
    fileReader.onload = () => {
      if (typeof fileReader.result === "string")
        form.value.profile_picture = fileReader.result;
    };
  }
};

// Reset avatar image
const resetAvatar = () => {
  form.value.profile_picture = null;
};

// 👉 Drawer close
const closeNavigationDrawer = () => {
  emit("update:isDrawerOpen", false);
  nextTick(() => {
    refForm.value?.reset();
    refForm.value?.resetValidation();
  });
};

// Clear server errors when user modifies a field
const clearServerError = (fieldName) => {
  if (serverErrors.value[fieldName]) {
    serverErrors.value[fieldName] = null;
  }
};

const onSubmit = () => {
  serverErrors.value = {};

  refForm.value?.validate().then(({ valid }) => {
    if (valid) {
      console.log("Form data:", JSON.parse(JSON.stringify(form.value)));
      axiosAdmin
        .post("/users", JSON.parse(JSON.stringify(form.value)))
        .then((response) => {
          // Clear server errors on success
          serverErrors.value = {};
          emit("userData", {
            user_added: true,
          });
          emit("update:isDrawerOpen", false);
          nextTick(() => {
            refForm.value?.reset();
            refForm.value?.resetValidation();
          });
        })
        .catch((error) => {
            // Dynamically assign server errors based on response
            serverErrors.value = Object.keys(error.data.errors).reduce(
              (acc, key) => {
                acc[key] = error.data.errors[key][0]; // Take the first error message
                return acc;
              },
              {}
            );
            // Trigger re-validation to show server errors
            refForm.value?.validate();
        
        });
    }
  });
};

// Fetch roles on mount
onMounted(() => {
  fetchRoles();
});

const fetchRoles = async () => {
  try {
    const response = await axiosAdmin.get("/roles");
    roles.value = response.data.map((role) => ({
      title: role.display_name || role.name,
      value: role.name,
    }));
  } catch (error) {
    console.error("Error fetching roles:", error);
  }
};
</script>

<template>
  <VNavigationDrawer
    data-allow-mismatch
    temporary
    :width="900"
    location="end"
    class="scrollable-content"
    :model-value="props.isDrawerOpen"
    @update:model-value="handleDrawerModelValueUpdate"
  >
    <!-- 👉 Title -->
    <AppDrawerHeaderSection
      title="Add New User"
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
              <VCol cols="6">
                <AppTextField
                  v-model="form.first_name"
                  :rules="[requiredValidator, serverErrorValidator('first_name')]"
                  label="First Name"
                  placeholder="John Doe"
                />
              </VCol>

              <VCol cols="6">
                <AppTextField
                  v-model="form.last_name"
                  :rules="[requiredValidator]"
                  label="Last Name"
                  placeholder="John Doe"
                />
              </VCol>

              <!-- 👉 Email -->
              <VCol cols="6">
                <AppTextField
                  v-model="form.email"
                  :rules="[requiredValidator, emailValidator, serverErrorValidator('email')]"
                  label="Email"
                  placeholder="johndoe@email.com"
                  @update:model-value="clearServerError('email')"
                />
              </VCol>

              <!-- 👉 Mobile Number -->
              <VCol cols="6">
                <AppTextField
                  v-model="form.mobile_number"
                  :rules="[requiredValidator, serverErrorValidator('mobile_number')]"
                  label="Mobile Number"
                  placeholder="+1-541-754-3010"
                  @update:model-value="clearServerError('mobile_number')"
                />
              </VCol>

              <!-- Other fields... -->

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
