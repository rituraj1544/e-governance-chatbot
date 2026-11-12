import { Link } from 'react-router-dom'

export default function Footer() {
  return <footer className="site-footer"><div className="footer-inner"><div><Link className="brand footer-brand" to="/"><span className="brand-mark">से</span><span><strong>SevaSetu</strong><small>Information made easier</small></span></Link><p>Clear English, Hindi, and Hinglish information for everyday public services.</p></div><div className="footer-links"><Link to="/schemes">Browse services</Link><Link to="/faqs">FAQs</Link><Link to="/chat">Ask a question</Link><Link to="/admin/login">Administration</Link></div></div><div className="footer-bottom"><span>Academic project · Informational use only</span><span>Always confirm requirements with an official source.</span></div></footer>
}
