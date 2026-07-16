<template>
  <form class="flex flex-col gap-4 w-full" @submit.prevent="submit">
    <div class="flex flex-col gap-1">
      <label for="name" class="text-sm text-surface-400">{{ $t('name_label') }}</label>
      <InputText id="name" v-model="name" :placeholder="$t('name_placeholder')" :invalid="!!errors.name" />
      <small v-if="errors.name" class="text-red-400">{{ errors.name }}</small>
    </div>

    <div class="flex flex-col gap-1">
      <label for="email" class="text-sm text-surface-400">{{ $t('email_label') }}</label>
      <InputText id="email" v-model="email" :placeholder="$t('email_placeholder')" :invalid="!!errors.email" />
      <small v-if="errors.email" class="text-red-400">{{ errors.email }}</small>
    </div>

    <Message v-if="conflictError" severity="warn" :closable="false">
      {{ conflictError }}
    </Message>

    <div class="flex flex-col sm:flex-row gap-2">
      <Button type="submit" :label="$t('book_button')" :loading="loading" class="flex-1" />
      <Button type="button" :label="$t('cancel_button')" severity="secondary" @click="$emit('cancel')" />
    </div>
  </form>
</template>

<script setup lang="ts">
import InputText from 'primevue/inputtext'
import Message from 'primevue/message'

const props = defineProps<{
  date: Date
  timeSlot: string
}>()

const emit = defineEmits<{
  cancel: []
  submitted: [appointment: Record<string, unknown>]
}>()

const { apiBase } = useApi()
const { locale } = useI18n()

const name = ref('')
const email = ref('')
const loading = ref(false)
const errors = ref<Record<string, string>>({})
const conflictError = ref('')

async function submit() {
  errors.value = {}
  conflictError.value = ''
  loading.value = true

  const formattedDate = `${props.date.getFullYear()}-${String(props.date.getMonth() + 1).padStart(2, '0')}-${String(props.date.getDate()).padStart(2, '0')}`

  try {
    const response = await $fetch<{ data: Record<string, unknown> }>(`${apiBase}/api/appointments`, {
      method: 'POST',
      headers: { 'Accept-Language': locale.value },
      body: {
        name: name.value,
        email: email.value,
        date: formattedDate,
        time_slot: props.timeSlot,
      },
    })

    emit('submitted', response.data)
  } catch (e: unknown) {
    if (e && typeof e === 'object' && 'data' in e) {
      const err = e as { status: number; data: { message?: string; errors?: Record<string, string[]> } }

      if (err.status === 422 && err.data.errors) {
        const fieldErrors = err.data.errors
        for (const [field, messages] of Object.entries(fieldErrors)) {
          errors.value[field] = messages[0]
        }
      } else if (err.status === 409 && err.data.message) {
        conflictError.value = err.data.message
      } else {
        conflictError.value = err.data?.message || 'An unexpected error occurred'
      }
    }
  } finally {
    loading.value = false
  }
}
</script>
