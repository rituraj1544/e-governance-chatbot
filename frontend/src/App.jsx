import { Navigate, Route, Routes } from 'react-router-dom'
import Navbar from './components/Navbar.jsx'
import Footer from './components/Footer.jsx'
import ProtectedRoute from './components/ProtectedRoute.jsx'
import AdminProtectedRoute from './components/AdminProtectedRoute.jsx'
import Home from './pages/Home.jsx'
import Login from './pages/Login.jsx'
import Signup from './pages/Signup.jsx'
import ForgotPassword from './pages/ForgotPassword.jsx'
import ResetPassword from './pages/ResetPassword.jsx'
import UserChat from './pages/UserChat.jsx'
import ChatHistory from './pages/ChatHistory.jsx'
import Profile from './pages/Profile.jsx'
import AdminLogin from './pages/AdminLogin.jsx'
import AdminDashboard from './pages/AdminDashboard.jsx'
import ManageSchemes from './pages/ManageSchemes.jsx'
import ManageFAQs from './pages/ManageFAQs.jsx'
import ManageUsers from './pages/ManageUsers.jsx'
import Analytics from './pages/Analytics.jsx'
import Schemes from './pages/Schemes.jsx'
import Faqs from './pages/Faqs.jsx'
import AdminLayout from './components/AdminLayout.jsx'

export default function App() {
  return <div className="app-shell"><Navbar /><main className="page-main"><Routes>
    <Route path="/" element={<Home />} /><Route path="/schemes" element={<Schemes />} /><Route path="/faqs" element={<Faqs />} />
    <Route path="/chat" element={<UserChat />} /><Route path="/login" element={<Login />} /><Route path="/signup" element={<Signup />} />
    <Route path="/forgot-password" element={<ForgotPassword />} /><Route path="/reset-password" element={<ResetPassword />} /><Route path="/admin/login" element={<AdminLogin />} />
    <Route element={<ProtectedRoute />}><Route path="/history" element={<ChatHistory />} /><Route path="/profile" element={<Profile />} /></Route>
    <Route element={<AdminProtectedRoute />}><Route element={<AdminLayout />}><Route path="/admin" element={<AdminDashboard />} /><Route path="/admin/schemes" element={<ManageSchemes />} /><Route path="/admin/faqs" element={<ManageFAQs />} /><Route path="/admin/users" element={<ManageUsers />} /><Route path="/admin/analytics" element={<Analytics />} /></Route></Route>
    <Route path="*" element={<Navigate to="/" replace />} />
  </Routes></main><Footer /></div>
}
