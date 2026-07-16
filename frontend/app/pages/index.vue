<template>
  <div
    class="min-h-screen flex flex-col items-center justify-center p-4 sm:p-8 relative"
    :style="{
      backgroundImage: 'url(/bg.gif)',
      backgroundSize: 'cover',
      backgroundPosition: 'center',
      backgroundRepeat: 'no-repeat',
      backgroundAttachment: 'fixed',
    }"
  >
    <div class="absolute inset-0 bg-black/60 pointer-events-none" />
    <h1 class="font-display text-4xl sm:text-5xl text-center mb-6 title-flicker">
      {{ $t('title') }}
    </h1>
    <div class="flex flex-col items-center gap-8 max-w-2xl lg:max-w-4xl w-full bg-black/50 backdrop-blur-sm rounded-2xl p-4 sm:p-8">
      <div class="flex flex-col sm:flex-row w-full justify-between items-center sm:items-start gap-4">
        <div class="text-center sm:text-left flex-1">
          <p class="text-surface-400">{{ $t('subtitle') }}</p>
        </div>
        <SelectButton
          :model-value="locale"
          :options="localeOptions"
          option-label="name"
          option-value="code"
          @update:model-value="setLocale"
        />
      </div>

      <BookingConfirmation
        v-if="bookedAppointment"
        :appointment="bookedAppointment"
        @rebook="resetAll"
      />

      <template v-else>
        <div class="flex flex-col lg:flex-row gap-8 w-full">
          <div class="lg:w-1/2 flex justify-center">
            <AppointmentCalendar
              v-model="selectedDate"
              :label="$t('choose_date')"
            />
          </div>

          <div class="lg:w-1/2 flex flex-col items-center gap-8">
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
          </div>
        </div>
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
  clearNuxtData()
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

<style scoped>
.title-flicker {
  color: #f5f0e8;
  animation: flicker 3s ease-in-out infinite;
}

@keyframes flicker {
  0%, 100% {
    opacity: 1;
    text-shadow: 0 0 20px rgba(245, 240, 232, 0.3), 0 0 40px rgba(245, 240, 232, 0.15);
  }
  50% {
    opacity: 0.88;
    text-shadow: 0 0 30px rgba(245, 240, 232, 0.45), 0 0 60px rgba(245, 240, 232, 0.25);
  }
}

@media (prefers-reduced-motion: reduce) {
  .title-flicker {
    animation: none;
  }
}
</style>
