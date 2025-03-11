<script setup>
import { defineEmits, defineProps, ref, toRaw, watch } from "vue";

const props = defineProps({
  schedule: {
    type: Object,
    required: false,
    default: () => ({
      selectedDays: [],
      timeSlots: {},
    }),
  },
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
  selectedDays: {
    type: Array,
    default: () => [],
  },
});

const emit = defineEmits(["submit", "update:isDialogVisible", "dateTimeRestriction"]);

const schedule = ref(structuredClone(toRaw(props.schedule)));

// Watch for changes in the selectedDays prop
watch(
  () => props.selectedDays,
  (newSelectedDays) => {
    // Clear existing data
    schedule.value.selectedDays = [];
    schedule.value.timeSlots = {};

    // Map the selectedDays data to the schedule object
    newSelectedDays.forEach((dayTime) => {
      const { day, from_time, to_time, time_period } = dayTime;

      // Add the day to selectedDays if it doesn't already exist
      if (!schedule.value.selectedDays.includes(day)) {
        schedule.value.selectedDays.push(day);
      }

      // Initialize time slots for the day if not already done
      if (!schedule.value.timeSlots[day]) {
        schedule.value.timeSlots[day] = {
          from1: "",
          to1: "",
          from2: "",
          to2: "",
        };
      }

      // Map the time slots based on the time period
      if (time_period === "Range 1") {
        schedule.value.timeSlots[day].from1 = from_time;
        schedule.value.timeSlots[day].to1 = to_time;
      } else if (time_period === "Range 2") {
        schedule.value.timeSlots[day].from2 = from_time;
        schedule.value.timeSlots[day].to2 = to_time;
      }
    });
  },
  { immediate: true } // Trigger the watcher immediately when the component is created
);

// Watch for changes in the schedule prop
watch(
  () => props.schedule,
  () => {
    schedule.value = structuredClone(toRaw(props.schedule));
  }
);

// Watch for deselected days and remove their data
watch(
  () => schedule.value.selectedDays,
  (newDays, oldDays) => {
    const removedDays = oldDays.filter((day) => !newDays.includes(day));
    removedDays.forEach((day) => {
      delete schedule.value.timeSlots[day]; // Remove the time slots
    });
  },
  { deep: true }
);

// Handle form submission
const formSubmit = () => {
  const formattedData = [];
  let isValid = true;

  schedule.value.selectedDays.forEach((day) => {
    if (schedule.value.timeSlots[day]) {
      const slots = schedule.value.timeSlots[day];

      // Validate Range 1 time selection
      if ((slots.from1 && !slots.to1) || (!slots.from1 && slots.to1)) {
        alert(`Please select both "From" and "To" time for Range 1 on ${day}`);
        isValid = false;
        return;
      }

      // Validate Range 2 time selection
      if ((slots.from2 && !slots.to2) || (!slots.from2 && slots.to2)) {
        alert(`Please select both "From" and "To" time for Range 2 on ${day}`);
        isValid = false;
        return;
      }

      if (slots.from1 && slots.to1) {
        formattedData.push({
          day: day,
          from_time: slots.from1,
          to_time: slots.to1,
          time_period: "Range 1",
        });
      }

      if (slots.from2 && slots.to2) {
        formattedData.push({
          day: day,
          from_time: slots.from2,
          to_time: slots.to2,
          time_period: "Range 2",
        });
      }
    }
  });

  if (isValid) {
    console.log(formattedData);
    dialogModelValueUpdate(false)
    emit("dateTimeRestriction", formattedData);

  }
};

// Update modal visibility
const dialogModelValueUpdate = (val) => {
  emit("update:isDialogVisible", val);
};

// Define available days
const daysOfWeek = [
  "Monday",
  "Tuesday",
  "Wednesday",
  "Thursday",
  "Friday",
  "Saturday",
  "Sunday",
];

// Initialize time slots when a day is selected
const handleDaySelection = () => {
  schedule.value.selectedDays.forEach((day) => {
    if (!schedule.value.timeSlots[day]) {
      schedule.value.timeSlots[day] = {
        from1: "",
        to1: "",
        from2: "",
        to2: "",
      };
    }
  });
};
</script>

<template>
  <VDialog
    :width="$vuetify.display.smAndDown ? 'auto' : 800"
    :model-value="props.isDialogVisible"
    @update:model-value="dialogModelValueUpdate"
  >
    <DialogCloseBtn @click="dialogModelValueUpdate(false)" />

    <VCard class="pa-2 pa-sm-10">
      <VCardItem class="text-center">
        <VCardTitle>
          <h4 class="text-h4 mb-2">Select Working Days & Hours</h4>
        </VCardTitle>
        <p class="text-body-1 mb-0">
          Choose days and set available time slots.
        </p>
      </VCardItem>

      <VCardText class="pt-6">
        <VForm @submit.prevent="formSubmit">
          <VRow>
            <!-- Day Selection -->
            <VCol cols="12">
              <VAutocomplete
                v-model="schedule.selectedDays"
                :items="daysOfWeek"
                label="Select Days"
                multiple
                chips
                @update:model-value="handleDaySelection"
              />
            </VCol>

            <!-- Time Inputs -->
            <template v-for="day in schedule.selectedDays" :key="day">
              <VCol cols="12">
                <strong>{{ day }}</strong>
              </VCol>

              <VCol cols="6">
                <VTextField
                  v-model="schedule.timeSlots[day].from1"
                  label="From (Range 1)"
                  type="time"
                  @input="
                    schedule.timeSlots[day].to1 =
                      schedule.timeSlots[day].to1 || ''
                  "
                />
              </VCol>
              <VCol cols="6">
                <VTextField
                  v-model="schedule.timeSlots[day].to1"
                  label="To (Range 1)"
                  type="time"
                  @input="
                    schedule.timeSlots[day].from1 =
                      schedule.timeSlots[day].from1 || ''
                  "
                />
              </VCol>
              <VCol cols="6">
                <VTextField
                  v-model="schedule.timeSlots[day].from2"
                  label="From (Range 2)"
                  type="time"
                  @input="
                    schedule.timeSlots[day].to2 =
                      schedule.timeSlots[day].to2 || ''
                  "
                />
              </VCol>
              <VCol cols="6">
                <VTextField
                  v-model="schedule.timeSlots[day].to2"
                  label="To (Range 2)"
                  type="time"
                  @input="
                    schedule.timeSlots[day].from2 =
                      schedule.timeSlots[day].from2 || ''
                  "
                />
              </VCol>
            </template>

            <!-- Actions -->
            <VCol cols="12" class="text-center">
              <VBtn class="me-4" type="submit"> Submit </VBtn>
              <VBtn
                color="secondary"
                variant="tonal"
                @click="dialogModelValueUpdate(false)"
              >
                Cancel
              </VBtn>
            </VCol>
          </VRow>
        </VForm>
      </VCardText>
    </VCard>
  </VDialog>
</template>
