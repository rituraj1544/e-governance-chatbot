import axios from 'axios'

// In development, use local backend or VITE_API_URL.
// In production on Vercel, always use relative path '' so requests are proxied same-origin.
export const API_ORIGIN = import.meta.env.DEV
  ? (import.meta.env.VITE_API_URL || 'http://localhost:8000').replace(/\/$/, '')
  : ''
let csrfRequest
const api = axios.create({
  baseURL: `${API_ORIGIN}/api`, withCredentials: true, withXSRFToken: true,
  headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
})

export async function csrfCookie() {
  if (!csrfRequest) {
    csrfRequest = axios.get(`${API_ORIGIN}/sanctum/csrf-cookie`, { withCredentials: true })
      .catch(() => { csrfRequest = null })
  }
  await csrfRequest
}

api.interceptors.request.use(async config => {
  const token = localStorage.getItem('token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  if (['post', 'put', 'patch', 'delete'].includes(config.method?.toLowerCase())) await csrfCookie()
  return config
})

export default api
