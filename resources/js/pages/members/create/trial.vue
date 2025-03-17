<script setup>
import { requiredValidator } from "@/@core/utils/validators";
import axiosAdmin from "@/composables/axios/axiosAdmin";
import avatar1 from "@images/avatars/avatar-1.png";
import { computed, onMounted, ref } from "vue";

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

const resetAvatar = () => {
  form.value.profile_picture = null;
  if (refInputEl.value) {
    if (Array.isArray(refInputEl.value)) {
      refInputEl.value.forEach((el) => (el.value = ""));
    } else {
      refInputEl.value.value = "";
    }
  }
};

// Fetch and initialize fields
const fetchFieldValidations = async () => {
  try {
    const response = await axiosAdmin.get("/field-validations");
    fields.value = response.filter((field) => field.trial === "YES");

    fields.value.forEach((field) => {
      if (!form.value.hasOwnProperty(field.field_key)) {
        form.value[field.field_key] = "";
      }
    });
  } catch (error) {
    console.error("Error fetching fields:", error);
  }
};

// Group fields by 'group' dynamically
const groupedFields = computed(() => {
  return fields.value.reduce((acc, field) => {
    if (!acc[field.group]) acc[field.group] = [];
    acc[field.group].push(field);
    return acc;
  }, {});
});

onMounted(fetchFieldValidations);
</script>

<template>
  <VCard flat>
    <VCardItem class="pb-4">
      <VCardTitle>Prospect</VCardTitle>
    </VCardItem>
    <VDivider />

    <VCardText>
      <VForm ref="refForm">
        <VRow
          v-for="(fieldsGroup, groupName) in groupedFields"
          :key="groupName"
        >
          <VCol cols="12">
            <h3>{{ groupName }}</h3>
            <VDivider class="mb-3" />
          </VCol>

          <VCol
            v-for="field in fieldsGroup"
            :key="field.field_key"
            cols="12"
            :md="field.field_key === 'photo' ? 12 : 4"
          >
            <!-- Profile Picture Upload -->
            <template v-if="field.field_key === 'photo'">
              <VCol cols="12">
                <VCardText class="d-flex">
                  <VAvatar
                    rounded
                    size="100"
                    class="me-6"
                    :image="form.profile_picture || accountDataLocal.avatarImg"
                  />
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
            <template v-else-if="field.input_type === 'date'">
              <AppDateTimePicker
                :rules="[requiredValidator]"
                v-model="form[field.field_key]"
                :label="field.field_name"
                :placeholder="field.field_name"
              />
            </template>

            <!-- Gender Selection -->
            <template v-else-if="field.input_type === 'dropdown'">
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

            <!-- Gender Selection -->
            <template v-else-if="field.input_type === 'textarea'">
              <AppTextarea
                v-model="form[field.field_key]"
                :rules="[requiredValidator]"
                :label="field.field_name"
                :placeholder="field.field_name"
                rows="2"
              />
            </template>

            <!-- Toggle (Switch) -->
            <template v-else-if="field.input_type === 'toggle'">
              <VSwitch
                v-model="form[field.field_key]"
                :label="field.field_name"
                color="primary"
                inset
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
        </VRow>

        <!-- Submit Button -->
        <VRow>
          <VCol cols="12">
            <VBtn type="submit" class="me-3"> Submit </VBtn>
          </VCol>
        </VRow>
      </VForm>
    </VCardText>
  </VCard>
</template>
