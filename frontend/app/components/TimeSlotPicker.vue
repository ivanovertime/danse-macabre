<template>
  <div class="flex flex-col items-center gap-4">
    <label class="text-sm text-surface-400">{{ label }}</label>

    <div v-if="pending" class="text-surface-400">
      <ProgressSpinner style="width: 32px; height: 32px" />
    </div>

    <div v-else-if="slots" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-2">
      <Button
        v-for="slot in slots"
        :key="slot.time"
        :label="slot.time"
        :disabled="!slot.available"
        :severity="modelValue === slot.time ? 'primary' : 'secondary'"
        :outlined="modelValue !== slot.time"
        @click="$emit('update:modelValue', slot.time)"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import ProgressSpinner from 'primevue/progressspinner'

const props = defineProps<{
  date: Date
  modelValue: string | null
  label?: string
}>()

defineEmits<{
  'update:modelValue': [value: string]
}>()

const { apiBase } = useApi()

const formattedDate = computed(() => {
  const d = props.date
  return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`
})

const { data, pending } = useFetch<{ data: Array<{ time: string; available: boolean }> }>(
  () => `${apiBase}/api/slots/${formattedDate.value}`,
  { watch: [formattedDate] }
)

const slots = computed(() => data.value?.data ?? [])
</script>
