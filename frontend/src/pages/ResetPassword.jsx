import { useState } from 'react'
import { Link, useSearchParams } from 'react-router-dom'
import api from '../services/api.js'
import FormInput from '../components/FormInput.jsx'

export default function ResetPassword() {
  const [params] = useSearchParams(); const [password, setPassword] = useState(''); const [confirm, setConfirm] = useState(''); const [busy, setBusy] = useState(false); const [message, setMessage] = useState(''); const [error, setError] = useState('')
  async function submit(e) { e.preventDefault(); setBusy(true); setError(''); try { const result = await api.post('/auth/reset-password', { token: params.get('token'), email: params.get('email'), password, password_confirmation: confirm }); setMessage(result.data.message) } catch (err) { setError(err.response?.data?.message || Object.values(err.response?.data?.errors || {}).flat()[0] || 'The reset link could not be used.') } finally { setBusy(false) } }
  return <section className="auth-single"><div className="auth-card"><span className="eyebrow">ACCOUNT RECOVERY</span><h2>Choose a new password</h2>{message ? <><div className="alert alert-success">{message}</div><Link className="button button-primary button-full" to="/login">Continue to log in</Link></> : <form onSubmit={submit} className="form-stack">{error && <div className="alert alert-error">{error}</div>}<FormInput id="password" label="New password" type="password" required minLength={10} value={password} onChange={e => setPassword(e.target.value)} /><FormInput id="confirm" label="Confirm new password" type="password" required value={confirm} onChange={e => setConfirm(e.target.value)} /><button className="button button-primary button-full" disabled={busy}>{busy ? 'Updating…' : 'Update password'}</button></form>}</div></section>
}
