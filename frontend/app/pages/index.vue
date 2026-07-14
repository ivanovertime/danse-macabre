<template>
  <div class="min-h-screen flex flex-col items-center justify-center p-8">
    <div class="flex flex-col items-center gap-8 max-w-2xl w-full">
      <div class="text-center">
        <h1 class="text-3xl font-bold mb-2">Danse Macabre</h1>
        <p class="text-surface-400">The ultimate solution to all your problems</p>
      </div>

      <AppointmentCalendar
        v-model="selectedDate"
        label="Choose your farewell date"
      />

      <TimeSlotPicker
        v-if="selectedDate"
        :key="selectedDate.toISOString()"
        :date="selectedDate"
        v-model="selectedSlot"
        label="Select your final hour"
      />

      <BookingForm
        v-if="selectedDate && selectedSlot"
        :date="selectedDate"
        :time-slot="selectedSlot"
        @submitted="onSubmitted"
        @cancel="reset"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
const selectedDate = ref<Date | null>(null)
const selectedSlot = ref<string | null>(null)

watch(selectedDate, () => {
  selectedSlot.value = null
})

function onSubmitted(appointment: Record<string, unknown>) {
  console.log('Appointment booked:', appointment)
  // Phase 5: redirect to confirmation screen
}

function reset() {
  selectedDate.value = null
  selectedSlot.value = null
}
</script>
