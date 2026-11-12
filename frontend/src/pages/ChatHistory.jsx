import { useEffect, useState } from 'react'
import api from '../services/api.js'
import Loading from '../components/Loading.jsx'

export default function ChatHistory() {
  const [rows, setRows] = useState([]); const [loading, setLoading] = useState(true); const [error, setError] = useState('')
  async function load() { try { const { data } = await api.get('/chat-history'); setRows(data.data || []) } catch { setError('Chat history could not be loaded.') } finally { setLoading(false) } }
  useEffect(() => { load() }, [])
  async function remove(id) { if (!window.confirm('Delete this saved conversation?')) return; try { await api.delete(`/chat-history/${id}`); setRows(items => items.filter(item => item.id !== id)) } catch { setError('This conversation could not be deleted.') } }
  return <section className="content-page"><div className="page-title-row"><div><span className="eyebrow">YOUR ACCOUNT</span><h1>Chat history</h1><p>Questions you have asked SevaSetu while signed in.</p></div></div>{error && <div className="alert alert-error">{error}</div>}{loading ? <Loading label="Loading conversations…" /> : rows.length ? <div className="history-list">{rows.map(row => <article className="history-card" key={row.id}><div className="history-meta"><span>{row.intent?.replaceAll('_', ' ') || 'General question'}</span><time>{new Date(row.created_at).toLocaleString()}</time><button className="text-button danger-text" onClick={() => remove(row.id)}>Delete</button></div><h3>{row.query}</h3><p>{row.response}</p></article>)}</div> : <div className="empty-state"><span>◌</span><h2>No saved conversations yet</h2><p>When you chat while signed in, your conversations will appear here.</p><a className="button button-primary" href="/chat">Start a conversation</a></div>}</section>
}
