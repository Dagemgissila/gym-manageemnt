<script setup>
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'

const props = defineProps({
  isDrawerOpen: {
    type: Boolean,
    required: true,
  },
})

const emit = defineEmits([
  'update:isDrawerOpen',
  'userData',
])

const isFormValid = ref(false)
const refForm = ref()
const first_name = ref('')
const last_name = ref('')
const email = ref('')
const mobile_number = ref('')
const password = ref()
const profile_picture = ref('')
const date_of_birth = ref()
const address = ref()
const emergency_contact_name = ref()
const emergency_contact_phone = ref()
const hire_date = ref()
const salary = ref()
const assigned_location = ref()
const role = ref()
const status=ref();


// 👉 drawer close
const closeNavigationDrawer = () => {
  emit('update:isDrawerOpen', false)
  nextTick(() => {
    refForm.value?.reset()
    refForm.value?.resetValidation()
  })
}

const onSubmit = () => {
  refForm.value?.validate().then(({ valid }) => {
    if (valid) {
      emit('userData', {
        first_name: first_name.value,
        last_name: last_name.value,
        email: email.value,
        mobile_number: mobile_number.value,
        password: password.value,
        profile_picture: profile_picture.value,
        date_of_birth: date_of_birth.value,
        address: address.value,
        emergency_contact_name: emergency_contact_name.value,
        emergency_contact_phone: emergency_contact_phone.value,
        hire_date: hire_date.value,
        salary: salary.value,
        assigned_location: assigned_location.value,
        role: role.value,
      })
      emit('update:isDrawerOpen', false)
      nextTick(() => {
        refForm.value?.reset()
        refForm.value?.resetValidation()
      })
    }
  })
}

const handleDrawerModelValueUpdate = val => {
  emit('update:isDrawerOpen', val)
}
</script>

<template>
  <VNavigationDrawer
    data-allow-mismatch
    temporary
    :width="400"
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
          <VForm
            ref="refForm"
            v-model="isFormValid"
            @submit.prevent="onSubmit"
          >
            <VRow>
              <!-- 👉 Full name -->
              <VCol cols="12">
                <AppTextField
                  v-model="first_name"
                  :rules="[requiredValidator]"
                  label="First Name"
                  placeholder="John Doe"
                />
              </VCol>

              <VCol cols="12">
                <AppTextField
                  v-model="last_name"
                  :rules="[requiredValidator]"
                  label="Last Name"
                  placeholder="John Doe"
                />
              </VCol>



              <!-- 👉 Email -->
              <VCol cols="12">
                <AppTextField
                  v-model="email"
                  :rules="[requiredValidator, emailValidator]"
                  label="Email"
                  placeholder="johndoe@email.com"
                />
              </VCol>

              <!-- 👉 company -->
              <VCol cols="12">
                <AppTextField
                  v-model="mobile_number"
                  :rules="[requiredValidator]"
               label="Contact"
                  placeholder="+1-541-754-3010"
                />
              </VCol>

              <!-- 👉 Country -->
              <VCol cols="12">
                <AppSelect
                  v-model="address"
                  label="Address"
                  placeholder="Select Country"
                  :rules="[requiredValidator]"
                  :items="['USA', 'UK', 'India', 'Australia']"
                />
              </VCol>

              <!-- 👉 Contact -->
              <VCol cols="12">
                <AppTextField
                  v-model="mobile_number"
                  type="number"
                  :rules="[requiredValidator]"
                  label="Contact"
                  placeholder="+1-541-754-3010"
                />
              </VCol>

              <!-- 👉 Role -->
              <VCol cols="12">
                <AppSelect
                  v-model="role"
                  label="Select Role"
                  placeholder="Select Role"
                  :rules="[requiredValidator]"
                  :items="[{ title: 'Admin', value: 'admin' }, { title: 'Member', value: 'member' }, { title: 'Trainner', value: 'trainner' }]"

                />
              </VCol>

              <!-- 👉 Plan -->
              <VCol cols="12">
                <AppTextField
                  v-model="emergency_contact_name"
                  label="Emergency Contact Name"
                  placeholder="Emergency Contact Name"
                  :rules="[requiredValidator]"
                />
              </VCol>

              <VCol cols="12">
                <AppTextField
                  v-model="emergency_contact_phone"
                  label="Emergency Contact Number"
                  placeholder="Emergency Contact Number"
                  :rules="[requiredValidator]"
                />
              </VCol>

              <!-- 👉 Status -->
              <VCol cols="12">
                <AppSelect
                  v-model="status"
                  label="Select Status"
                  placeholder="Select Status"
                  :rules="[requiredValidator]"
                  :items="[{ title: 'Active', value: 'active' }, { title: 'Inactive', value: 'inactive' }, { title: 'Pending', value: 'pending' }]"
                />
              </VCol>

              <!-- 👉 Submit and Cancel -->
              <VCol cols="12">
                <VBtn
                  type="submit"
                  class="me-3"
                >
                  Submit
                </VBtn>
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
