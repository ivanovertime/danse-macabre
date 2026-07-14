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
    />
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

const minDate = new Date()
</script>
