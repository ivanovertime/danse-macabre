<template>
  <div class="flex flex-col items-center gap-4">
    <label class="text-sm text-surface-400">{{ label }}</label>
    <DatePicker
      :model-value="modelValue"
      inline
      :disabled-days="[0, 6]"
      :min-date="minDate"
      :manual-input="false"
      date-format="dd/mm/yy"
      @update:model-value="$emit('update:modelValue', $event)"
      @month-change="onMonthChange"
    >
      <template #date="slotProps">
        <div class="flex flex-col items-center">
          <span>{{ slotProps.date.day }}</span>
          <span
            v-if="isCurrentMonth(slotProps.date) && getAvailability(slotProps.date)"
            class="text-[10px] leading-none"
            :class="getAvailability(slotProps.date)!.available === 0 ? 'text-red-400' : 'text-surface-400'"
          >
            {{ getAvailability(slotProps.date)!.available === 0 ? $t('no_slots') : getAvailability(slotProps.date)!.available }}
          </span>
        </div>
      </template>
    </DatePicker>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  modelValue: Date | null
  label?: string
}>()

defineEmits<{
  'update:modelValue': [value: Date]
}>()

const { t } = useI18n()
const { apiBase } = useApi()

const now = new Date()
const minDate = now
const displayedMonth = ref(now.getMonth())
const displayedYear = ref(now.getFullYear())

interface MonthAvailability {
  available: number
  total: number
}

const monthAvailability = ref<Record<string, MonthAvailability>>({})

async function fetchMonthAvailability() {
  try {
    const data = await $fetch<{ data: Record<string, MonthAvailability> }>(
      `${apiBase}/api/slots/month/${displayedYear.value}/${displayedMonth.value + 1}`
    )
    monthAvailability.value = data.data
  } catch {
    monthAvailability.value = {}
  }
}

watch([displayedMonth, displayedYear], fetchMonthAvailability, { immediate: true })

function getAvailability(date: { day: number; month: number; year: number }): MonthAvailability | undefined {
  const key = `${date.year}-${String(date.month + 1).padStart(2, '0')}-${String(date.day).padStart(2, '0')}`
  return monthAvailability.value[key]
}

function isCurrentMonth(date: { month: number; year: number }): boolean {
  return date.month === displayedMonth.value && date.year === displayedYear.value
}

function onMonthChange(event: { month: number; year: number }) {
  displayedMonth.value = event.month - 1
  displayedYear.value = event.year
}
</script>
