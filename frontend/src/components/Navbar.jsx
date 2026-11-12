import { Link, NavLink, useNavigate } from 'react-router-dom'
import { useAuth } from '../context/AuthContext.jsx'

export default function Navbar() {
  const { user, logout } = useAuth()
  const navigate = useNavigate()
  const signOut = async () => { await logout(); navigate('/') }
  return <header className="site-header"><div className="nav-wrap">
    <Link className="brand" to="/"><span className="brand-mark">से</span><span><strong>SevaSetu</strong><small>Citizen information portal</small></span></Link>
    <nav className="main-nav" aria-label="Main navigation"><NavLink to="/schemes">Schemes</NavLink><NavLink to="/faqs">FAQs</NavLink><NavLink to="/chat">Ask SevaSetu</NavLink></nav>
    <div className="nav-actions">{user ? <><Link className="nav-profile" to={user.role === 'admin' ? '/admin' : '/profile'}>{user.role === 'admin' ? 'Admin console' : user.name.split(' ')[0]}</Link><button className="button button-quiet button-small" onClick={signOut}>Sign out</button></> : <><Link className="nav-login" to="/login">Log in</Link><Link className="button button-primary button-small" to="/signup">Create account</Link></>}</div>
  </div></header>
}
