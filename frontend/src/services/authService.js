import api, { csrfCookie } from './api.js'

export async function registerAccount(payload) { await csrfCookie(); return (await api.post('/auth/register', payload)).data.user }
export async function loginAccount(payload) { await csrfCookie(); return (await api.post('/auth/login', payload)).data.user }
export async function loginAdmin(payload) { await csrfCookie(); return (await api.post('/admin/auth/login', payload)).data.user }
export async function currentUser() { return (await api.get('/auth/user')).data.user }
export async function logoutAccount() { await csrfCookie(); return api.post('/auth/logout') }
