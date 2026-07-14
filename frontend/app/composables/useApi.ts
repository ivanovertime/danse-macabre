export function useApi() {
  const { apiBase } = useRuntimeConfig().public
  return { apiBase }
}
