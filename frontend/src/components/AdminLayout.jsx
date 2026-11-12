import { NavLink, Outlet } from 'react-router-dom'
import { useAuth } from '../context/AuthContext.jsx'

export default function AdminLayout() {
  const { user } = useAuth()
  return <div className="admin-layout"><aside className="admin-sidebar"><div className="admin-brand"><span className="brand-mark">से</span><div><b>SevaSetu</b><small>Administration</small></div></div><span className="sidebar-label">WORKSPACE</span><nav><NavLink end to="/admin">Overview</NavLink><NavLink to="/admin/schemes">Service guides</NavLink><NavLink to="/admin/faqs">FAQs</NavLink><NavLink to="/admin/users">Citizens</NavLink><NavLink to="/admin/analytics">Analytics & chats</NavLink></nav><div className="sidebar-account"><span className="account-avatar">{user?.name?.[0] || 'A'}</span><div><b>{user?.name}</b><small>Administrator</small></div></div></aside><div className="admin-workspace"><div className="admin-topbar"><span>Administration / <b>Workspace</b></span><a href="/">View public site ↗</a></div><Outlet /></div></div>
}
