import React, { useEffect } from 'react';
import { useNavigate, useLocation } from 'react-router-dom';

const AuthSuccess = () => {
  const navigate = useNavigate();
  const location = useLocation();

  useEffect(() => {
    const params = new URLSearchParams(location.search);
    const token = params.get('token');
    if (token) {
      // store token (you can change to cookie if preferred)
      localStorage.setItem('token', token);
      // navigate to dashboard
      navigate('/dashboard', { replace: true });
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
