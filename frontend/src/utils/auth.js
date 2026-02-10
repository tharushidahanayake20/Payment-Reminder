// Authentication utility functions
import API_BASE_URL from '../config/api';

// Check if user is authenticated by verifying session with backend
export const isAuthenticated = () => {
  const userData = localStorage.getItem('userData');

  if (!userData) {
    return false;
  }

  // Session validity is checked by the backend on each request
  // We rely on cookies for authentication, not tokens
  return true;
};

// Clear all session data
export const clearSession = () => {
  const darkMode = localStorage.getItem('darkMode'); // Preserve theme preference
  localStorage.clear();
  if (darkMode) {
    localStorage.setItem('darkMode', darkMode);
  }
};

// Get current user data
export const getCurrentUser = () => {
  try {
    const userData = localStorage.getItem('userData');
    return userData ? JSON.parse(userData) : null;
  } catch (error) {
    console.error('Error parsing user data:', error);
    return null;
  }
};

// Get user role
export const getUserRole = () => {
  const user = getCurrentUser();
  return user?.role || 'caller';
};

// Check if user has required role
export const hasRole = (requiredRole) => {
  const userRole = getUserRole();
  return userRole === requiredRole;
};
