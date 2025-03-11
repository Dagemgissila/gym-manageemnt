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

const emit = defineEmits([
  "submit",
  "update:isDialogVisible",
  "dateTimeRestriction",
]);

const schedule = ref(structuredClone(toRaw(props.schedule)));

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

// Watch for changes in selectedDays prop
watch(
  () => props.selectedDays,
  (newSelectedDays) => {
    schedule.value.selectedDays = [];
    schedule.value.timeSlots = {};

    newSelectedDays.forEach((dayTime) => {
      const { day, from_time, to_time, time_period } = dayTime;
      if (!schedule.value.selectedDays.includes(day)) {
        schedule.value.selectedDays.push(day);
      }
      if (!schedule.value.timeSlots[day]) {
        schedule.value.timeSlots[day] = {
          from1: "",
          to1: "",
          from2: "",
          to2: "",
        };
      }
      if (time_period === "Range 1") {
        schedule.value.timeSlots[day].from1 = from_time;
        schedule.value.timeSlots[day].to1 = to_time;
      } else if (time_period === "Range 2") {
        schedule.value.timeSlots[day].from2 = from_time;
        schedule.value.timeSlots[day].to2 = to_time;
      }
    });
  },
  { immediate: true }
);

// Watch for changes in schedule prop
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
      delete schedule.value.timeSlots[day];
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

      if ((slots.from1 && !slots.to1) || (!slots.from1 && slots.to1)) {
        alert(`Please select both "From" and "To" time for Range 1 on ${day}`);
        isValid = false;
        return;
      }

      if ((slots.from2 && !slots.to2) || (!slots.from2 && slots.to2)) {
        alert(`Please select both "From" and "To" time for Range 2 on ${day}`);
        isValid = false;
        return;
      }

      if (slots.from1 && slots.to1) {
        formattedData.push({
          day,
          from_time: slots.from1,
          to_time: slots.to1,
          time_period: "Range 1",
        });
      }
      if (slots.from2 && slots.to2) {
        formattedData.push({
          day,
          from_time: slots.from2,
          to_time: slots.to2,
          time_period: "Range 2",
        });
      }
    }
  });

  if (isValid) {
    emit("dateTimeRestriction", formattedData);
    emit("update:isDialogVisible", false);
  }
};

// Update modal visibility
const dialogModelValueUpdate = (val) => {
  emit("update:isDialogVisible", val);
};

// Handle "Select All" functionality
const handleDaySelection = () => {
  if (schedule.value.selectedDays.includes("Select All")) {
    schedule.value.selectedDays = [...daysOfWeek];
  } else if (schedule.value.selectedDays.length < daysOfWeek.length) {
    schedule.value.selectedDays = schedule.value.selectedDays.filter(
      (day) => day !== "Select All"
    );
  }

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

// Apply Monday's time slots to all selected days
const applyMondayToAll = () => {
  if (!schedule.value.timeSlots["Monday"]) {
    alert("Please set Monday's time slots first.");
    return;
  }

  const mondaySlots = schedule.value.timeSlots["Monday"];

  if (
    !mondaySlots.from1 &&
    !mondaySlots.to1 &&
    !mondaySlots.from2 &&
    !mondaySlots.to2
  ) {
    alert("Monday's time slots are empty!");
    return;
  }

  schedule.value.selectedDays.forEach((day) => {
    if (day !== "Monday") {
      schedule.value.timeSlots[day] = { ...mondaySlots };
    }
  });

  alert("Monday's schedule applied to all selected days!");
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
                :items="['Select All', ...daysOfWeek]"
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
                />
              </VCol>
              <VCol cols="6">
                <VTextField
                  v-model="schedule.timeSlots[day].to1"
                  label="To (Range 1)"
                  type="time"
                />
              </VCol>
              <VCol cols="6">
                <VTextField
                  v-model="schedule.timeSlots[day].from2"
                  label="From (Range 2)"
                  type="time"
                />
              </VCol>
              <VCol cols="6">
                <VTextField
                  v-model="schedule.timeSlots[day].to2"
                  label="To (Range 2)"
                  type="time"
                />
              </VCol>

              <!-- Show the Apply to All button only for Monday -->
              <VCol v-if="day == 'Monday'" cols="12" class="text-center">
                <VBtn color="primary" @click="applyMondayToAll">
                  Apply to All Days
                </VBtn>
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
