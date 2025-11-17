import React, { useEffect } from 'react';
import { useNavigate, useLocation } from 'react-router-dom';
import { jwtDecode } from 'jwt-decode';

const AuthSuccess = () => {
  const navigate = useNavigate();
  const location = useLocation();

  useEffect(() => {
    const params = new URLSearchParams(location.search);
    const token = params.get('token');
    if (token) {
      try {
        // Decode token to get user info
        const decoded = jwtDecode(token);
        
        // Store token
        localStorage.setItem('token', token);
        
        // Store user data
        localStorage.setItem('userData', JSON.stringify({
          id: decoded.id,
          email: decoded.email,
          name: decoded.name,
          avatar: decoded.avatar,
          role: decoded.role || 'caller'
        }));
        
        // Navigate to dashboard
        navigate('/dashboard', { replace: true });
      } catch (err) {
        console.error('Token decode error:', err);
        navigate('/login', { replace: true });
      }
    } else {
      // no token — send to login
      navigate('/login', { replace: true });
    }
  }, [location.search, navigate]);

  return (
    <div style={{display:'flex',alignItems:'center',justifyContent:'center',height:'100vh'}}>
      <div>
        <h3>Signing you in…</h3>
      </div>
    </div>
  );
};

export default AuthSuccess;
