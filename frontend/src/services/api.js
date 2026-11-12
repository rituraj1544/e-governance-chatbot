import axios from 'axios'

export const API_ORIGIN = (import.meta.env.VITE_API_URL || 'http://localhost:8000').replace(/\/$/, '')
let csrfRequest
const api = axios.create({
  baseURL: `${API_ORIGIN}/api`, withCredentials: true, withXSRFToken: true,
  headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
})

export async function csrfCookie() {
  if (!csrfRequest) {
    csrfRequest = axios.get(`${API_ORIGIN}/sanctum/csrf-cookie`, { withCredentials: true })
      .catch(error => { csrfRequest = null; throw error })
  }
  await csrfRequest
}

api.interceptors.request.use(async config => {
  if (['post', 'put', 'patch', 'delete'].includes(config.method?.toLowerCase())) await csrfCookie()
  return config
})

export default api
