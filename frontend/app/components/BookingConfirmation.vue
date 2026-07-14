<template>
  <div class="flex flex-col items-center gap-6 text-center">
    <h2 class="text-2xl font-bold">Appointment Confirmed</h2>
    <p class="text-surface-400 max-w-md">
      Your final appointment has been booked. The ultimate solution to all your problems awaits.
    </p>

    <Card class="w-full max-w-sm">
      <template #content>
        <div class="flex flex-col gap-3">
          <div class="flex justify-between">
            <span class="text-surface-400">Name</span>
            <span class="font-medium">{{ appointment.name }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-surface-400">Email</span>
            <span class="font-medium">{{ appointment.email }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-surface-400">Date</span>
            <span class="font-medium">{{ formattedDate }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-surface-400">Time</span>
            <span class="font-medium">{{ appointment.time_slot }}</span>
          </div>
        </div>
      </template>
    </Card>

    <Button
      label="I choose to suffer"
      severity="secondary"
      @click="$emit('rebook')"
    />
  </div>
</template>

<script setup lang="ts">
import Card from 'primevue/card'

const props = defineProps<{
  appointment: Record<string, unknown>
}>()

defineEmits<{
  rebook: []
}>()

const formattedDate = computed(() => {
  const d = new Date(props.appointment.date as string)
  return d.toLocaleDateString('en-US', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  })
})
</script>
