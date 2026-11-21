import axios from 'axios'

const defaultOrigin = import.meta.env.DEV ? 'http://localhost:8000' : ''
export const API_ORIGIN = (import.meta.env.VITE_API_URL !== undefined ? import.meta.env.VITE_API_URL : defaultOrigin).replace(/\/$/, '')
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
  if (['post', 'put', 'patch', 'delete'].includes(config.method?.toLowerCase())) await csrfCookie()
  return config
})

export default api
