import React, {useState} from 'react';
import './Register.css';
import logo from '../assets/logo.png';
import { useNavigate } from 'react-router-dom';

const Register = () => {
  const [form, setForm] = useState({ name:'', email:'', phone:'', password:'', confirmPassword:'' });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const navigate = useNavigate();

  const onChange = e => setForm({...form, [e.target.name]: e.target.value });

  const submit = async (e) =>{
    e.preventDefault();
    setError('');
    if (form.password !== form.confirmPassword) return setError('Passwords do not match');
    setLoading(true);
    try{
      const res = await fetch('http://localhost:4000/auth/register', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(form)
      });
      const data = await res.json();
      if (!res.ok) throw new Error(data.message || 'Registration failed');
      // registration success -> save token and go to dashboard
      localStorage.setItem('token', data.token);
      navigate('/dashboard');
    }catch(err){
      setError(err.message);
    }finally{setLoading(false)}
  }

  return (
    <div className="login-page">
      <div className="container">
        <div className="left-section">
          <img src={logo} className="logo" alt="logo" />
          <h1 className="welcome">Create account</h1>
        </div>

        <div className="right-section">
          <div className="register-card">
            <form onSubmit={submit}>
              <input name="name" value={form.name} onChange={onChange} placeholder="Full name" required />
              <input name="email" value={form.email} onChange={onChange} placeholder="Email" type="email" required />
              <input name="phone" value={form.phone} onChange={onChange} placeholder="Phone number" />
              <input name="password" value={form.password} onChange={onChange} placeholder="Password" type="password" required />
              <input name="confirmPassword" value={form.confirmPassword} onChange={onChange} placeholder="Confirm password" type="password" required />
              <button type="submit" disabled={loading}>{loading ? 'Creating...' : 'Register'}</button>
              {error && <p style={{color:'red'}}>{error}</p>}
              <p className="sn"style={{marginTop:10}}>Already have an account? <a href="#" onClick={(e)=>{e.preventDefault(); navigate('/login')}}>Sign in</a></p>
            </form>
          </div>
        </div>
      </div>
    </div>
  );
}

export default Register;
