import { createContext, useCallback, useContext, useEffect, useMemo, useState } from 'react'
import api from '../services/api.js'
import { currentUser, loginAccount, loginAdmin as loginAdminService, logoutAccount, registerAccount } from '../services/authService.js'

const AuthContext = createContext(null)

export function AuthProvider({ children }) {
  const [user, setUser] = useState(null)
  const [loading, setLoading] = useState(true)
  const refreshUser = useCallback(async () => {
    try { setUser(await currentUser()) } catch { setUser(null) } finally { setLoading(false) }
  }, [])
  useEffect(() => { refreshUser() }, [refreshUser])
  const value = useMemo(() => ({
    user, loading, refreshUser,
    async register(payload) { const next = await registerAccount(payload); setUser(next); return next },
    async login(payload) { const next = await loginAccount(payload); setUser(next); return next },
    async loginAdmin(payload) { const next = await loginAdminService(payload); setUser(next); return next },
    async logout() { await logoutAccount(); setUser(null) },
    async updateProfile(payload) { const next = (await api.patch('/profile', payload)).data.user; setUser(next); return next },
  }), [user, loading, refreshUser])
  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>
}

export function useAuth() {
  const context = useContext(AuthContext)
  if (!context) throw new Error('useAuth must be used inside AuthProvider')
  return context
}
