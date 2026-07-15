<template>
  <div class="flex flex-col items-center gap-6 text-center">
    <h2 class="text-2xl font-bold">{{ $t('confirmation_title') }}</h2>
    <p class="text-surface-400 max-w-md">
      {{ $t('confirmation_description') }}
    </p>

    <Card class="w-full max-w-sm">
      <template #content>
        <div class="flex flex-col gap-3">
          <div class="flex justify-between">
            <span class="text-surface-400">{{ $t('label_name') }}</span>
            <span class="font-medium">{{ appointment.name }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-surface-400">{{ $t('label_email') }}</span>
            <span class="font-medium">{{ appointment.email }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-surface-400">{{ $t('label_date') }}</span>
            <span class="font-medium">{{ formattedDate }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-surface-400">{{ $t('label_time') }}</span>
            <span class="font-medium">{{ appointment.time_slot }}</span>
          </div>
        </div>
      </template>
    </Card>

    <Button
      :label="$t('cancel_button')"
      severity="secondary"
      @click="$emit('rebook')"
    />
  </div>
</template>

<script setup lang="ts">
import Card from 'primevue/card'

const { t, locale } = useI18n()

const props = defineProps<{
  appointment: Record<string, unknown>
}>()

defineEmits<{
  rebook: []
}>()

const formattedDate = computed(() => {
  const d = new Date(props.appointment.date as string)
  return d.toLocaleDateString(t('locale'), {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  })
})
</script>
