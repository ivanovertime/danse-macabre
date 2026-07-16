<template>
  <div class="flex flex-col items-center gap-4 w-full">
    <label class="text-sm text-surface-400">{{ label }}</label>
    <div class="bg-black/50 backdrop-blur-sm rounded-2xl border border-white/10 p-2 sm:p-3 w-full max-w-full overflow-hidden"
         style="max-width: min(100%, 28rem)">
      <DatePicker :model-value="modelValue" inline :disabled-days="[0, 6]" :min-date="minDate" :manual-input="false"
        date-format="dd/mm/yy" panel-class="!bg-transparent !border-0 !shadow-none"
        :pt="{
          panel: { style: { padding: '0.5rem', minWidth: '0', width: '100%' } },
          header: { style: { background: 'transparent', border: '0', padding: '0.5rem', gap: '0.5rem', minWidth: '0', flexWrap: 'nowrap', overflow: 'hidden' } },
          title: { style: { gap: '0.25rem', minWidth: '0', overflow: 'hidden' } },
          selectMonth: { style: {
            background: 'transparent',
            color: 'rgb(212, 212, 212)',
            padding: '0.25rem 0.5rem',
            borderRadius: '0.375rem',
            fontSize: 'clamp(0.7rem, 2.5vw, 0.875rem)',
            fontWeight: '600',
            cursor: 'pointer',
            border: '0',
            transition: 'color 0.15s, background-color 0.15s',
            whiteSpace: 'nowrap',
            minWidth: '0',
            flexShrink: '1',
            overflow: 'hidden',
            textOverflow: 'ellipsis',
          }},
          selectYear: { style: {
            background: 'transparent',
            color: 'rgb(212, 212, 212)',
            padding: '0.25rem 0.5rem',
            borderRadius: '0.375rem',
            fontSize: 'clamp(0.7rem, 2.5vw, 0.875rem)',
            fontWeight: '600',
            cursor: 'pointer',
            border: '0',
            transition: 'color 0.15s, background-color 0.15s',
            whiteSpace: 'nowrap',
            minWidth: '0',
            flexShrink: '1',
          }},
          pcPrevButton: { props: { severity: 'secondary', text: true, rounded: true, size: 'small' } },
          pcNextButton: { props: { severity: 'secondary', text: true, rounded: true, size: 'small' } },
          dayView: { style: { width: '100%' } },
          dayCell: { style: { padding: 'clamp(0.05rem, 0.8vw, 0.25rem)' } },
          day: { style: {
            width: 'clamp(1.75rem, calc((100vw - 9rem) / 7), 2.5rem)',
            height: 'clamp(1.75rem, calc((100vw - 9rem) / 7), 2.5rem)',
            fontSize: 'clamp(0.6rem, 2.2vw, 0.875rem)',
          }},
          weekday: { style: { fontSize: 'clamp(0.5rem, 1.8vw, 0.75rem)' } },
        }" @update:model-value="$emit('update:modelValue', $event)" @month-change="onMonthChange">
        <template #date="slotProps">
          <div class="flex flex-col items-center">
            <span>{{ slotProps.date.day }}</span>
            <span v-if="isCurrentMonth(slotProps.date) && getAvailability(slotProps.date)"
              class="text-[10px] leading-none"
              :class="getAvailability(slotProps.date)!.available === 0 ? 'text-red-400' : 'text-surface-400'">
              {{ getAvailability(slotProps.date)!.available === 0 ? $t('no_slots') :
                getAvailability(slotProps.date)!.available }}
            </span>
          </div>
        </template>
      </DatePicker>
    </div>
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
