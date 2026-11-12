import { useState } from 'react'
import { Link, useNavigate } from 'react-router-dom'
import FormInput from '../components/FormInput.jsx'
import { useAuth } from '../context/AuthContext.jsx'

export default function AdminLogin() {
  const { loginAdmin } = useAuth(); const navigate = useNavigate(); const [email, setEmail] = useState(''); const [password, setPassword] = useState(''); const [busy, setBusy] = useState(false); const [error, setError] = useState('')
  async function submit(e) { e.preventDefault(); setBusy(true); setError(''); try { const user = await loginAdmin({ email, password }); if (user.role !== 'admin') throw new Error('Administrator access is required.'); navigate('/admin', { replace: true }) } catch (err) { setError(err.response?.data?.message || err.message || 'Sign in failed.') } finally { setBusy(false) } }
  return <section className="auth-single"><div className="auth-card admin-login-card"><span className="eyebrow">RESTRICTED AREA</span><h2>Administrator sign in</h2><p className="muted">Use the administrator account provisioned for this project.</p>{error && <div className="alert alert-error">{error}</div>}<form onSubmit={submit} className="form-stack"><FormInput id="email" label="Admin email" type="email" required value={email} onChange={e => setEmail(e.target.value)} autoComplete="username" /><FormInput id="password" label="Password" type="password" required value={password} onChange={e => setPassword(e.target.value)} autoComplete="current-password" /><button className="button button-primary button-full" disabled={busy}>{busy ? 'Signing in…' : 'Sign in to admin'}</button></form><p className="auth-switch"><Link to="/">← Back to public site</Link></p></div></section>
}
