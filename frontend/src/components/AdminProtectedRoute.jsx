import { Navigate, Outlet } from 'react-router-dom'
import { useAuth } from '../context/AuthContext.jsx'

export default function AdminProtectedRoute() {
  const { user, loading } = useAuth()
  if (loading) return <div className="route-loading">Checking your session…</div>
  if (!user) return <Navigate to="/admin/login" replace />
  return user.role === 'admin' ? <Outlet /> : <Navigate to="/chat" replace />
}
