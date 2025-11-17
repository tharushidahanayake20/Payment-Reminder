import React, { useState } from "react";
import "./Login.css";
import logo from "../assets/logo.png";
import { FcGoogle } from "react-icons/fc";
import { FaUserShield } from "react-icons/fa";
import { useNavigate } from "react-router-dom";
import { jwtDecode } from 'jwt-decode';
import API_BASE_URL from '../config/api';

const Login = () => {
  const navigate = useNavigate();

  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [otp, setOtp] = useState('');
  const [showOtpInput, setShowOtpInput] = useState(false);
  const [error, setError] = useState('');
  const [message, setMessage] = useState('');
  const [loading, setLoading] = useState(false);

  const handleSignIn = async (e) => {
    e.preventDefault();
    setError('');
    setMessage('');
    setLoading(true);
    try {
      const res = await fetch(`${API_BASE_URL}/auth/login`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password })
      });
      const data = await res.json();
      if (!res.ok) throw new Error(data.message || 'Login failed');
      
      // Login success - now show OTP input
      setMessage(data.message);
      setShowOtpInput(true);
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  const verifyOtp = async (e) => {
    e.preventDefault();
    setError('');
    setLoading(true);
    try {
      const res = await fetch(`${API_BASE_URL}/auth/verify-otp`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, otp, isPasswordReset: false })
      });
      const data = await res.json();
      if (!res.ok) throw new Error(data.message || 'OTP verification failed');
      
      // OTP verified - decode token and save user data
      const decoded = jwtDecode(data.token);
      localStorage.setItem('token', data.token);
      localStorage.setItem('userData', JSON.stringify({
        id: decoded.id,
        email: decoded.email,
        name: decoded.name,
        avatar: decoded.avatar,
        role: decoded.role || 'caller'
      }));
      
      navigate('/dashboard');
    } catch(err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="login-page">
      <div className="container">

        {/* LEFT SECTION */}
        <div className="left-section">
          <img src={logo} className="logo" alt="logo" />

          <h1 className="welcome">
            Welcome! <br /> SLT <br /> Pay Reminder Portal
          </h1>
        </div>

        {/* RIGHT SECTION */}
        <div className="right-section">

          {/* LOGIN CARD */}
          <div className="login-card">
            <h2>Login</h2>

            {!showOtpInput ? (
              <form onSubmit={handleSignIn}>
                <label>Email</label>
                <input value={email} onChange={e=>setEmail(e.target.value)} type="email" placeholder="username@gmail.com" required />

                <label>Password</label>
                <input value={password} onChange={e=>setPassword(e.target.value)} type="password" placeholder="Password" required />

                <span className="fp" onClick={()=>navigate('/forgot-password')}>Forgot Password?</span>

                <button className="signin-btn" type="submit" disabled={loading}>
                  {loading ? 'Signing In...' : 'Sign In'}
                </button>
                {error && <p style={{color:'red'}}>{error}</p>}
                {message && <p style={{color:'green'}}>{message}</p>}

                <p className="rg">Don't have an account? <a href="#" onClick={(e)=>{e.preventDefault(); navigate('/register')}}>Register</a></p>

                <p className="divider">or continue with</p>

                <button
                  type="button"
                  onClick={() => {
                    const redirect = encodeURIComponent(window.location.origin);
                    window.location.href = `${API_BASE_URL}/auth/google?redirect=${redirect}`;
                  }}
                  className="google-btn"
                >
                  <FcGoogle size={20} />
                </button>
              </form>
            ) : (
              <form onSubmit={verifyOtp}>
                <h3>Verify Your Identity</h3>
                <p style={{fontSize:'14px', marginBottom:'20px'}}>Enter the 6-digit code sent to your phone</p>
                <input 
                  type="text" 
                  value={otp} 
                  onChange={e => setOtp(e.target.value)} 
                  placeholder="Enter 6-digit OTP" 
                  maxLength="6"
                  required 
                />
                <button type="submit" disabled={loading} className="signin-btn">
                  {loading ? 'Verifying...' : 'Verify OTP'}
                </button>
                {error && <p style={{color:'red'}}>{error}</p>}
                <p className="rg" style={{marginTop:10}}>
                  <a href="#" onClick={(e)=>{e.preventDefault(); setShowOtpInput(false); setOtp(''); setMessage('');}}>Back to login</a>
                </p>
              </form>
            )}
          </div>

          {/* ADMIN BUTTON BELOW LOGIN CARD */}
          <button className="admin-btn">
            <FaUserShield size={20} /> Login as Admin</button>

        </div>
      </div>
    </div>
  );
};

export default Login;
