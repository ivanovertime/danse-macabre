<template>
  <div
    class="min-h-screen flex flex-col items-center justify-center p-8 relative"
    :style="{
      backgroundImage: 'url(/bg.gif)',
      backgroundSize: 'cover',
      backgroundPosition: 'center',
      backgroundRepeat: 'no-repeat',
      backgroundAttachment: 'fixed',
    }"
  >
    <div class="absolute inset-0 bg-black/60 pointer-events-none" />
    <div class="flex flex-col items-center gap-8 max-w-2xl w-full bg-black/50 backdrop-blur-sm rounded-2xl p-8">
      <div class="flex w-full justify-between items-start">
        <div class="text-center flex-1">
          <h1 class="text-3xl font-bold mb-2">Danse Macabre</h1>
          <p class="text-surface-400">{{ $t('subtitle') }}</p>
        </div>
      </div>
      <SelectButton
        :model-value="locale"
        :options="localeOptions"
        option-label="name"
        option-value="code"
        @update:model-value="setLocale"
      />

      <BookingConfirmation
        v-if="bookedAppointment"
        :appointment="bookedAppointment"
        @rebook="resetAll"
      />

      <template v-else>
        <AppointmentCalendar
          v-model="selectedDate"
          :label="$t('choose_date')"
        />

        <TimeSlotPicker
          v-if="selectedDate"
          :key="selectedDate.toISOString()"
          :date="selectedDate"
          v-model="selectedSlot"
          :label="$t('select_hour')"
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
import SelectButton from 'primevue/selectbutton'

const { locale } = useI18n()

const localeOptions = [
  { code: 'en', name: 'EN' },
  { code: 'es', name: 'ES' },
]

function setLocale(code: string | number) {
  locale.value = String(code)
}

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
