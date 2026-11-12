import { useEffect, useRef, useState } from 'react'
import { Link } from 'react-router-dom'
import api from '../services/api.js'
import { useAuth } from '../context/AuthContext.jsx'
import MessageBubble from '../components/MessageBubble.jsx'
import QuickButtons from '../components/QuickButtons.jsx'

const welcome = (language = 'en') => ({ role: 'bot', text: language === 'hi' ? 'नमस्ते! मैं सरकारी सेवाओं के बारे में उपलब्ध जानकारी ढूँढने में मदद कर सकता हूँ। आप किस बारे में जानना चाहते हैं?' : language === 'hinglish' ? 'Namaste! Main government services ke baare mein catalogue mein available information dhoondhne mein help kar sakta hoon. Aap kis service ke baare mein jaanna chahte hain?' : 'Hello! I can help you find information available in this service catalogue. What would you like to know?', time: new Date().toISOString() })

export default function UserChat() {
  const { user } = useAuth(); const [language, setLanguage] = useState('en'); const [messages, setMessages] = useState([welcome()]); const [query, setQuery] = useState(''); const [busy, setBusy] = useState(false); const [error, setError] = useState('')
  const bottom = useRef(null); const input = useRef(null)
  useEffect(() => { bottom.current?.scrollIntoView?.({ behavior: 'smooth' }) }, [messages, busy])
  useEffect(() => { if (!user) return; api.get('/chat-history?per_page=10').then(({ data }) => { if (data.data?.length) setMessages([...data.data].reverse().flatMap(row => [{ role: 'user', text: row.query, time: row.created_at }, { role: 'bot', text: row.response, source: row.scheme ? { type: 'scheme', id: row.scheme.id, title: row.language === 'hinglish' ? row.scheme.title_hinglish : row.scheme.title, url: row.scheme.official_source_url } : row.faq ? { type: 'faq', id: row.faq.id, title: row.language === 'hinglish' ? row.faq.question_hinglish : row.faq.question, url: row.faq.official_source_url } : null, time: row.created_at }])) }).catch(() => {}) }, [user])

  async function send(text = query) {
    const clean = text.trim(); if (!clean || busy) return
    setQuery(''); setError(''); setMessages(current => [...current, { role: 'user', text: clean, time: new Date().toISOString() }]); setBusy(true)
    try {
      const { data } = await api.post('/chatbot/query', { query: clean, language })
      const answer = data.answer
      setMessages(current => [...current, { role: 'bot', text: answer.reply, source: answer.source, time: new Date().toISOString() }])
    } catch (err) {
      setError(err.response?.data?.message || 'The chat service is not available right now. Please try again in a moment.')
    } finally { setBusy(false); input.current?.focus() }
  }

  function clear() { if (window.confirm('Clear the messages in this chat view? Saved history remains in your account.')) setMessages([welcome(language)]) }
  return <section className="chat-page"><div className="chat-heading"><div><span className="eyebrow">SERVICE INFORMATION ASSISTANT</span><h1>Ask SevaSetu</h1><p>Ask in English, Hindi, or Hinglish. Answers use the catalogue and link to official sources where available.</p></div><label className="language-select">Language<select value={language} onChange={e => { setLanguage(e.target.value); setMessages([welcome(e.target.value)]) }}><option value="en">English</option><option value="hi">हिन्दी</option><option value="hinglish">Hinglish</option></select></label></div>
    <div className="chat-layout"><div className="chat-panel"><div className="chat-toolbar"><div><span className="online-dot" /> SevaSetu assistant <small>General information</small></div><button className="button button-quiet button-small" onClick={clear}>Clear chat</button></div><div className="chat-messages" aria-live="polite">{messages.map((message, i) => <MessageBubble key={`${i}-${message.time}`} message={message} />)}{busy && <div className="typing"><span /><span /><span /> Finding relevant information…</div>}<div ref={bottom} /></div>{error && <div className="alert alert-error chat-error" role="alert">{error}</div>}<QuickButtons disabled={busy} onChoose={text => send(text)} /><form className="chat-composer" onSubmit={e => { e.preventDefault(); send() }}><input ref={input} value={query} onChange={e => setQuery(e.target.value)} placeholder={language === 'hi' ? 'अपना सवाल लिखें…' : language === 'hinglish' ? 'Apna sawaal yahan likho…' : 'Type your question…'} maxLength={2000} aria-label="Your question" /><button type="submit" disabled={busy || !query.trim()} aria-label="Send message">↑</button></form><div className="chat-disclaimer">SevaSetu is informational and does not submit applications or make official decisions.</div></div>
      <aside className="chat-side"><div className="side-card"><span className="side-icon">?</span><h3>Not sure where to begin?</h3><p>Choose a service and ask a plain-language question about it.</p><div className="side-links"><Link to="/schemes">Browse service guides <span>↗</span></Link><Link to="/faqs">Search common questions <span>↗</span></Link></div></div><div className="side-card side-card-blue"><span className="eyebrow">YOUR HISTORY</span><h3>Keep conversations handy</h3><p>{user ? 'Your chats are saved to your account.' : 'Sign in to save your personal chat history.'}</p><Link className="text-link" to={user ? '/history' : '/login'}>{user ? 'Open chat history' : 'Sign in'} <span>→</span></Link></div></aside></div></section>
}
