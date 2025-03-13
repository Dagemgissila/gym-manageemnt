<script setup>
import { requiredValidator } from "@/@core/utils/validators";
import axiosAdmin from "@/composables/axios/axiosAdmin";
import avatar1 from "@images/avatars/avatar-1.png";
import { onMounted } from "vue";
const accountData = {
  avatarImg: avatar1,
};

const fields = ref([]);
const form = ref({
  profile_picture: "",
  date_of_birth: null,
});

const accountDataLocal = ref(structuredClone(accountData));

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
  form.value.profile_picture = null;
  // Reset the file input's value
  if (refInputEl.value) {
    // If refInputEl is an array (from v-for), adjust accordingly:
    if (Array.isArray(refInputEl.value)) {
      refInputEl.value.forEach(el => el.value = '');
    } else {
      refInputEl.value.value = '';
    }
  }
};


// Fetch and initialize fields
const fetchFieldValidations = async () => {
  try {
    const response = await axiosAdmin.get("/field-validations");
    // Filter out 'photo' and initialize form
    fields.value = response.filter(
      (field) => field.prospect === "YES"
    );
    fields.value.forEach((field) => {
      if (!form.value.hasOwnProperty(field.field_key)) {
        form.value[field.field_key] = ""; // Initialize with default value
      }
    });
  } catch (error) {
    console.error("Error fetching fields:", error);
  }
};

onMounted(fetchFieldValidations);
</script>

<template>
  <VCard flat>
    <h1>Create</h1>
    <VCardText>
      <!-- 👉 Form -->
      <VForm ref="refForm">
        <VRow>
          <VCol v-for="field in fields" :key="field.field_key" :cols="field.field_key === 'photo' ? 12 : 4">

          <template v-if="field.field_key === 'photo'">
            <VCol cols="12">
            <VCardText class="d-flex">
              <!-- 👉 Avatar -->
              <VAvatar
                rounded
                size="100"
                class="me-6"
                :image="
                  form.profile_picture
                    ? form.profile_picture
                    : accountDataLocal.avatarImg
                "
              />

              <!-- 👉 Upload Photo -->
              <div class="d-flex flex-column justify-center gap-4">
                <div class="d-flex flex-wrap gap-4">
                  <VBtn
                    color="primary"
                    size="small"
                    @click="(refInputEl[0] || refInputEl).click()"
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
                    type="reset"
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

          </template>
            <!-- Date Picker -->
            <template v-else-if="field.field_key === 'date_of_birth'">
              <AppDateTimePicker
                :rules="[requiredValidator]"
                v-model="form[field.field_key]"
                :label="field.field_name"
                :placeholder="field.field_name"
              />
            </template>

            <!-- Gender Selection -->
            <template v-else-if="field.field_key === 'gender'">
              <AppSelect
                v-model="form[field.field_key]"
                :rules="[requiredValidator]"
                :label="field.field_name"
                :items="[
                  { title: 'Male', value: 'Male' },
                  { title: 'Female', value: 'Female' },
                ]"
                placeholder="field.field_name"

              />
            </template>

            <!-- Default Text Input -->
            <template v-else>
              <AppTextField
                :rules="[requiredValidator]"
                v-model="form[field.field_key]"
                :label="field.field_name"
                :placeholder="field.field_name"
              />
            </template>
          </VCol>


          <!-- 👉 Submit and Cancel -->
          <VCol cols="12">
            <VBtn type="submit" class="me-3"> Submit </VBtn>
          </VCol>
        </VRow>
      </VForm>
    </VCardText>
  </VCard>
</template>

<style lang="scss"></style>
