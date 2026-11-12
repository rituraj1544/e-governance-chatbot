export default function MessageBubble({ message }) {
  const mine = message.role === 'user'
  return <div className={`message-row ${mine ? 'message-mine' : ''}`}><div className={`message-avatar ${mine ? 'avatar-user' : 'avatar-bot'}`}>{mine ? 'Y' : 'स'}</div><div className="message-content"><div className={`message-bubble ${mine ? 'bubble-user' : 'bubble-bot'}`}>{message.text}{message.source?.url && <a className="source-link" href={message.source.url} target="_blank" rel="noreferrer">Official source ↗</a>}{message.source && !message.source.url && <small className="demo-source">No official source link is available for this answer.</small>}</div><time>{message.time ? new Date(message.time).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : 'Just now'}</time></div></div>
}
