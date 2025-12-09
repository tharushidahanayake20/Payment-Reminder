// API Configuration
// Development: Uses localhost:5173 with Vite proxy routing /api to backend:4000
// Production: Uses same port for frontend and backend, /api routes to backend
const API_BASE_URL = import.meta.env.VITE_API_URL ||  'http://localhost:5173';

export default API_BASE_URL;
