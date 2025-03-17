<script setup>
import axiosAdmin from "@/composables/axios/axiosAdmin";
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

const isFormValid = ref(false);
const refForm = ref();
const roles = ref([]);

// 👉 Form fields
const form = ref({
  first_name: "",
  last_name: "",
  email: "",
  mobile_number: "",
  date_of_birth: null, // Provide a default (could be '' or null)
  profile_picture: "",
  address: "",
  assigned_location: "",
  hire_date: null,
  salary: "",
  emergency_contact_name: "",
  emergency_contact_phone: "",
  role: "",
  status: "",
  gender: "",
  profile_picture: "",
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

// reset avatar image
const resetAvatar = () => {
  form.value.profile_picture = "";
  if (refInputEl.value) {
    refInputEl.value.value = "";
  }
};

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
      const userData = { ...props.selectedUser };
      delete userData.profile_picture; // Remove profile_picture from the object

      // If a new profile picture has been selected, add it to the data
      if (form.value.profile_picture) {
        userData.profile_picture = form.value.profile_picture;
      }

      console.log(userData);

      axiosAdmin
        .patch(`/users/${props.selectedUser.id}`, userData)
        .then((response) => {
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
          handleServerErrors(error);
          // Trigger re-validation to show server errors
          refForm.value?.validate();
        });
    }
  });
};

const fetchRoles = async () => {
  try {
    const response = await axiosAdmin.get("/roles");

    roles.value = response.data.map((role) => ({
      title: role.name, 
      value: role.name,
    }));
  } catch (error) {
    console.error("Error fetching roles:", error);
  }
};

onMounted(() => {
  fetchRoles();
});

const handleDrawerModelValueUpdate = (val) => {
  emit("update:isDrawerOpen", val);
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
              <VCol cols="12" md="6">
                <AppTextField
                  v-model="selectedUser.first_name"
                  :rules="[
                    requiredValidator,
                    serverErrorValidator('first_name'),
                  ]"
                  label="First Name"
                  placeholder="John Doe"
                />
              </VCol>

              <VCol cols="12" md="6">
                <AppTextField
                  v-model="selectedUser.last_name"
                  :rules="[
                    requiredValidator,
                    serverErrorValidator('last_name'),
                  ]"
                  label="Last Name"
                  placeholder="John Doe"
                />
              </VCol>

              <VCol cols="12">
                <VCardText class="d-flex">
                  <!-- 👉 Avatar -->
                  <VAvatar
                    rounded
                    size="100"
                    class="me-6"
                    :image="
                      form.profile_picture ||
                      selectedUser.profile_picture ||
                      accountData.avatarImg
                    "
                  />

                  <!-- 👉 Upload Photo -->
                  <div class="d-flex flex-column justify-center gap-4">
                    <div class="d-flex flex-wrap gap-4">
                      <VBtn
                        color="primary"
                        size="small"
                        @click="refInputEl?.click()"
                      >
                        <VIcon icon="tabler-cloud-upload" class="d-sm-none" />
                        <span class="d-none d-sm-block">Upload new photo</span>
                      </VBtn>

                      <input
                        ref="refInputEl"
                        type="file"
                        name="file"
                        accept=".jpeg,.png,.jpg,GIF"
                        hidden
                        @input="changeAvatar"
                      />

                      <VBtn
                        size="small"
                        color="secondary"
                        variant="tonal"
                        @click="resetAvatar"
                      >
                        <span class="d-none d-sm-block">Reset</span>
                        <VIcon icon="tabler-refresh" class="d-sm-none" />
                      </VBtn>
                    </div>

                    <p class="text-body-1 mb-0">
                      Allowed JPG, GIF or PNG. Max size of 800K
                    </p>
                  </div>
                </VCardText>
              </VCol>
              <!-- 👉 Email -->
              <VCol cols="12" md="6">
                <AppTextField
                  v-model="selectedUser.email"
                  :rules="[
                    requiredValidator,
                    emailValidator,
                    serverErrorValidator('email'),
                  ]"
                  label="Email"
                  placeholder="johndoe@email.com"
                />
              </VCol>

              <!-- 👉 company -->
              <VCol cols="12" md="6">
                <AppTextField
                  v-model="selectedUser.mobile_number"
                  :rules="[
                    requiredValidator,
                    serverErrorValidator('mobile_number'),
                  ]"
                  label="Mobile Number"
                  placeholder="+1-541-754-3010"
                />
              </VCol>

              <VCol cols="12" md="6">
                <AppDateTimePicker
                  v-model="selectedUser.date_of_birth"
                  :rules="[
                    requiredValidator,
                    serverErrorValidator('date_of_birth'),
                  ]"
                  label="Date of Birth"
                  placeholder="Select date"
                />
              </VCol>

              <!-- 👉 Role -->
              <VCol cols="12" md="6">
                <AppSelect
                  v-model="selectedUser.gender"
                  label="Gender"
                  placeholder="Gender"
                  :rules="[requiredValidator, serverErrorValidator('gender')]"
                  :items="[
                    { title: 'Male', value: 'Male' },
                    { title: 'Female', value: 'Female' },
                  ]"
                />
              </VCol>

              <VCol cols="12" md="6">
                <AppTextField
                  v-model="selectedUser.address"
                  :rules="[requiredValidator, serverErrorValidator('address')]"
                  label="Address"
                  placeholder="Address"
                />
              </VCol>

              <VCol cols="12" md="6">
                <AppTextField
                  v-model="selectedUser.assigned_location"
                  :rules="[
                    requiredValidator,
                    serverErrorValidator('assined_location'),
                  ]"
                  label="Assigned Location"
                  placeholder=""
                />
              </VCol>

              <VCol cols="12" md="6">
                <AppDateTimePicker
                  v-model="selectedUser.hire_date"
                  :rules="[
                    requiredValidator,
                    serverErrorValidator('hire_date'),
                  ]"
                  label="Hire Date"
                  placeholder="Select date"
                />
              </VCol>

              <VCol cols="12" md="6">
                <AppTextField
                  v-model="selectedUser.salary"
                  :rules="[requiredValidator, serverErrorValidator('salary')]"
                  label="Salary"
                  placeholder="$1000"
                />
              </VCol>

              <!-- 👉 Plan -->
              <VCol cols="12" md="6">
                <AppTextField
                  v-model="selectedUser.emergency_contact_name"
                  label="Emergency Contact Name"
                  placeholder="Emergency Contact Name"
                  :rules="[requiredValidator]"
                />
              </VCol>

              <VCol cols="12" md="6">
                <AppTextField
                  v-model="selectedUser.emergency_contact_phone"
                  label="Emergency Contact Number"
                  placeholder="Emergency Contact Number"
                  :rules="[requiredValidator]"
                />
              </VCol>

              <!-- 👉 Role -->
              <VCol cols="12" md="6">
                <AppSelect
                  v-model="selectedUser.role"
                  label="Select Role"
                  placeholder="Select Role"
                  :rules="[requiredValidator]"
                  :items="roles"
                />
              </VCol>

              <VCol cols="12" md="6">
                <VSwitch
                  v-model="selectedUser.status"
                  :label="`User Status`"
                  :true-value="1"
                  :false-value="0"
                />
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
