import { useState } from 'react'
import { Link } from 'react-router-dom'
import api from '../services/api.js'
import FormInput from '../components/FormInput.jsx'

export default function ForgotPassword() {
  const [email, setEmail] = useState(''); const [busy, setBusy] = useState(false); const [error, setError] = useState(''); const [sent, setSent] = useState(false)
  async function submit(e) { e.preventDefault(); setBusy(true); setError(''); try { await api.post('/auth/forgot-password', { email }); setSent(true) } catch (err) { setError(err.response?.data?.message || 'Please check the email address and try again.') } finally { setBusy(false) } }
  return <section className="auth-single"><div className="auth-card"><span className="eyebrow">ACCOUNT RECOVERY</span><h2>Reset your password</h2><p className="muted">Enter your account email. If it matches an account, we’ll send a reset link.</p>{sent ? <div className="alert alert-success">If an account exists, a reset link has been sent. In local development, check <code>backend/storage/logs/laravel.log</code>.</div> : <form className="form-stack" onSubmit={submit}>{error && <div className="alert alert-error">{error}</div>}<FormInput id="email" label="Email address" type="email" required value={email} onChange={e => setEmail(e.target.value)} autoComplete="email" /><button className="button button-primary button-full" disabled={busy}>{busy ? 'Sending…' : 'Send reset link'}</button></form>}<p className="auth-switch"><Link to="/login">Return to log in</Link></p></div></section>
}
