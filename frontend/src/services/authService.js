import api, { csrfCookie } from './api.js'

export async function registerAccount(payload) {
  await csrfCookie()
  const { data } = await api.post('/auth/register', payload)
  if (data.token) localStorage.setItem('token', data.token)
  return data.user
}

export async function loginAccount(payload) {
  await csrfCookie()
  const { data } = await api.post('/auth/login', payload)
  if (data.token) localStorage.setItem('token', data.token)
  return data.user
}

export async function loginAdmin(payload) {
  await csrfCookie()
  const { data } = await api.post('/admin/auth/login', payload)
  if (data.token) localStorage.setItem('token', data.token)
  return data.user
}

export async function currentUser() {
  return (await api.get('/auth/user')).data.user
}

export async function logoutAccount() {
  try {
    await csrfCookie()
    return await api.post('/auth/logout')
  } finally {
    localStorage.removeItem('token')
  }
}
