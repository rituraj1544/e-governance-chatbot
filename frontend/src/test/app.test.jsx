import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest'
import { render, screen, waitFor } from '@testing-library/react'
import userEvent from '@testing-library/user-event'
import { MemoryRouter, Route, Routes } from 'react-router-dom'
import { AuthProvider } from '../context/AuthContext.jsx'
import { currentUser, loginAccount, registerAccount } from '../services/authService.js'
import api from '../services/api.js'
import Signup from '../pages/Signup.jsx'
import Login from '../pages/Login.jsx'
import UserChat from '../pages/UserChat.jsx'
import ProtectedRoute from '../components/ProtectedRoute.jsx'
import AdminProtectedRoute from '../components/AdminProtectedRoute.jsx'

vi.mock('../services/authService.js', () => ({
  currentUser: vi.fn(),
  loginAccount: vi.fn(),
  loginAdmin: vi.fn(),
  logoutAccount: vi.fn(),
  registerAccount: vi.fn(),
}))

vi.mock('../services/api.js', () => ({
  default: { get: vi.fn(), post: vi.fn(), patch: vi.fn(), put: vi.fn(), delete: vi.fn() },
  API_ORIGIN: 'http://localhost:8000',
  csrfCookie: vi.fn(),
}))

function renderAt(path, routes, user = null) {
  currentUser.mockResolvedValue(user)
  return render(<MemoryRouter initialEntries={[path]}><AuthProvider><Routes>{routes}</Routes></AuthProvider></MemoryRouter>)
}

const loginRoutes = <><Route path="/login" element={<Login />} /><Route path="/chat" element={<p>Citizen chat page</p>} /></>

describe('citizen account and protected routes', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    currentUser.mockResolvedValue(null)
    registerAccount.mockResolvedValue({ id: 1, name: 'Asha Citizen', email: 'asha@example.test', role: 'citizen' })
    loginAccount.mockResolvedValue({ id: 1, name: 'Asha Citizen', email: 'asha@example.test', role: 'citizen' })
  })
  afterEach(() => vi.restoreAllMocks())

  it('requires terms acceptance and completes citizen signup', async () => {
    const user = userEvent.setup()
    renderAt('/signup', <><Route path="/signup" element={<Signup />} /><Route path="/chat" element={<p>Citizen chat page</p>} /></>)
    await user.type(await screen.findByLabelText('Full name'), 'Asha Citizen')
    await user.type(screen.getByLabelText('Email address'), 'asha@example.test')
    await user.type(screen.getByLabelText('Password'), 'CitizenPass123')
    await user.type(screen.getByLabelText('Confirm password'), 'CitizenPass123')
    await user.click(screen.getByRole('button', { name: 'Create account' }))
    expect(await screen.findByText('Please accept the terms before continuing.')).toBeInTheDocument()
    expect(registerAccount).not.toHaveBeenCalled()
    await user.click(screen.getByRole('checkbox'))
    await user.click(screen.getByRole('button', { name: 'Create account' }))
    expect(await screen.findByText('Citizen chat page')).toBeInTheDocument()
    expect(registerAccount).toHaveBeenCalledWith(expect.objectContaining({ email: 'asha@example.test' }))
    expect(registerAccount.mock.calls[0][0]).not.toHaveProperty('role')
  })

  it('shows a useful login error and accepts successful login', async () => {
    const user = userEvent.setup()
    loginAccount.mockRejectedValueOnce({ response: { data: { message: 'The email or password is incorrect.' } } })
    renderAt('/login', loginRoutes)
    await user.type(await screen.findByLabelText('Email address'), 'asha@example.test')
    await user.type(screen.getByLabelText('Password'), 'wrong-password')
    await user.click(screen.getByRole('button', { name: 'Log in' }))
    expect(await screen.findByRole('alert')).toHaveTextContent('The email or password is incorrect.')
    await user.clear(screen.getByLabelText('Password'))
    await user.type(screen.getByLabelText('Password'), 'CitizenPass123')
    await user.click(screen.getByRole('button', { name: 'Log in' }))
    expect(await screen.findByText('Citizen chat page')).toBeInTheDocument()
  })

  it('redirects logged-out visitors away from personal pages', async () => {
    renderAt('/history', <><Route element={<ProtectedRoute />}><Route path="/history" element={<p>Private history</p>} /></Route><Route path="/login" element={<p>Sign in to continue</p>} /></>)
    expect(await screen.findByText('Sign in to continue')).toBeInTheDocument()
    expect(screen.queryByText('Private history')).not.toBeInTheDocument()
  })

  it('blocks citizen access to administrator pages', async () => {
    const citizen = { id: 2, name: 'Asha', role: 'citizen' }
    renderAt('/admin', <><Route element={<AdminProtectedRoute />}><Route path="/admin" element={<p>Admin dashboard</p>} /></Route><Route path="/chat" element={<p>Citizen chat page</p>} /></>, citizen)
    expect(await screen.findByText('Citizen chat page')).toBeInTheDocument()
    expect(screen.queryByText('Admin dashboard')).not.toBeInTheDocument()
  })
})

describe('public chatbot', () => {
  beforeEach(() => {
    vi.clearAllMocks()
    currentUser.mockResolvedValue(null)
    api.get.mockResolvedValue({ data: { data: [] } })
  })

  it('sends the selected language and renders the returned answer', async () => {
    const user = userEvent.setup()
    api.post.mockResolvedValue({ data: { answer: { reply: 'आधार की जानकारी यहाँ उपलब्ध है।', source: { type: 'scheme', url: 'https://uidai.gov.in/en/enrolment-and-updates' } } } })
    renderAt('/chat', <Route path="/chat" element={<UserChat />} />)
    await screen.findByText(/Hello! I can help you find information/)
    await user.selectOptions(screen.getByLabelText('Language'), 'hi')
    const input = screen.getByRole('textbox', { name: 'Your question' })
    await user.type(input, 'आधार कार्ड की जानकारी')
    await user.keyboard('{Enter}')
    expect(await screen.findByText('आधार की जानकारी यहाँ उपलब्ध है।')).toBeInTheDocument()
    expect(screen.getByRole('link', { name: 'Official source ↗' })).toHaveAttribute('href', 'https://uidai.gov.in/en/enrolment-and-updates')
    expect(api.post).toHaveBeenCalledWith('/chatbot/query', expect.objectContaining({ language: 'hi' }))
  })

  it('offers Hinglish and sends the selected language code', async () => {
    const user = userEvent.setup()
    api.post.mockResolvedValue({ data: { answer: { reply: 'PM-KISAN status portal par check karein.', language: 'hinglish', source: null } } })
    renderAt('/chat', <Route path="/chat" element={<UserChat />} />)
    await screen.findByText(/Hello! I can help you find information/)
    await user.selectOptions(screen.getByLabelText('Language'), 'hinglish')
    await user.type(screen.getByRole('textbox', { name: 'Your question' }), 'PM Kisan ka status kaise check karun?')
    await user.keyboard('{Enter}')
    expect(await screen.findByText('PM-KISAN status portal par check karein.')).toBeInTheDocument()
    expect(api.post).toHaveBeenCalledWith('/chatbot/query', expect.objectContaining({ language: 'hinglish' }))
  })

  it('shows a clear API error when the chatbot cannot be reached', async () => {
    const user = userEvent.setup()
    api.post.mockRejectedValue({ response: { data: { message: 'Chat backend unavailable.' } } })
    renderAt('/chat', <Route path="/chat" element={<UserChat />} />)
    await screen.findByText(/Hello! I can help you find information/)
    await user.click(screen.getByRole('button', { name: 'Aadhaar' }))
    expect(await screen.findByRole('alert')).toHaveTextContent('Chat backend unavailable.')
  })
})
