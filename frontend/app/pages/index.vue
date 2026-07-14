<template>
  <div class="min-h-screen flex flex-col items-center justify-center p-8">
    <div class="flex flex-col items-center gap-8 max-w-2xl w-full">
      <div class="text-center">
        <h1 class="text-3xl font-bold mb-2">Danse Macabre</h1>
        <p class="text-surface-400">The ultimate solution to all your problems</p>
      </div>

      <BookingConfirmation
        v-if="bookedAppointment"
        :appointment="bookedAppointment"
        @rebook="resetAll"
      />

      <template v-else>
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
      </template>
    </div>
  </div>
</template>

<script setup lang="ts">
const selectedDate = ref<Date | null>(null)
const selectedSlot = ref<string | null>(null)
const bookedAppointment = ref<Record<string, unknown> | null>(null)

watch(selectedDate, () => {
  selectedSlot.value = null
})

function onSubmitted(appointment: Record<string, unknown>) {
  bookedAppointment.value = appointment
}

function reset() {
  selectedDate.value = null
  selectedSlot.value = null
}

function resetAll() {
  selectedDate.value = null
  selectedSlot.value = null
  bookedAppointment.value = null
}
</script>
