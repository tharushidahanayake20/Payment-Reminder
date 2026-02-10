import API_BASE_URL from '../config/api';
import { getCsrfToken } from './csrf';

/**
 * Enhanced fetch wrapper that uses cookie-based authentication with CSRF protection
 * @param {string} url - The API endpoint (will be prefixed with API_BASE_URL)
 * @param {object} options - Fetch options
 * @returns {Promise<Response>}
 */
export const secureFetch = async (url, options = {}) => {

    // For state-changing requests, fetch CSRF token first
    // EXCEPT for authentication routes which are excluded from CSRF validation
    const method = options.method?.toUpperCase() || 'GET';
    const csrfExcludedRoutes = ['/api/login', '/api/send-otp', '/api/verify-otp'];
    const needsCsrf = ['POST', 'PUT', 'PATCH', 'DELETE'].includes(method) &&
        !csrfExcludedRoutes.some(route => url.includes(route));

    if (needsCsrf) {
        await getCsrfToken();
    }


    const headers = {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        ...(options.headers || {}),
    };


    if (options.body instanceof FormData) {
        delete headers['Content-Type'];
    }

    const config = {
        ...options,
        headers,
        credentials: 'include', // Send cookies with every request
    };

    const fullUrl = url.startsWith('http') ? url : `${API_BASE_URL}${url}`;

    try {
        const response = await fetch(fullUrl, config);

        // Handle 401 Unauthorized - automatically redirect to login
        if (response.status === 401) {
            console.warn('401 Unauthorized - clearing auth and redirecting to login');
            localStorage.removeItem('userData');
            window.location.href = '/login';
            throw new Error('Unauthorized - Please login again');
        }

        // Handle 400 Bad Request - provide better error messages
        if (response.status === 400) {
            const errorData = await response.json().catch(() => ({ message: 'Bad Request' }));
            console.error('400 Bad Request:', errorData);
            throw new Error(errorData.message || 'Bad Request - Invalid data sent to server');
        }

        // Handle 500 Internal Server Error - log and display error
        if (response.status === 500) {
            const errorData = await response.json().catch(() => ({ message: 'Internal Server Error' }));
            console.error('500 Internal Server Error:', errorData);
            throw new Error(errorData.error || errorData.message || 'Server error occurred. Please try again.');
        }


        return response;
    } catch (error) {
        // If it's a network error or fetch failed
        if (error.message.includes('Failed to fetch') || error.message.includes('NetworkError')) {
            console.error('Network error - server may be down:', error);
            throw new Error('Cannot connect to server. Please check if the backend is running.');
        }
        console.error('API Request failed:', error);
        throw error;
    }
};

/**
 * Convenience methods for common HTTP verbs
 */
export const api = {
    get: (url, options = {}) => secureFetch(url, { ...options, method: 'GET' }),

    post: (url, data, options = {}) => secureFetch(url, {
        ...options,
        method: 'POST',
        body: data instanceof FormData ? data : JSON.stringify(data),
    }),

    put: (url, data, options = {}) => secureFetch(url, {
        ...options,
        method: 'PUT',
        body: data instanceof FormData ? data : JSON.stringify(data),
    }),

    delete: (url, options = {}) => secureFetch(url, { ...options, method: 'DELETE' }),

    patch: (url, data, options = {}) => secureFetch(url, {
        ...options,
        method: 'PATCH',
        body: JSON.stringify(data),
    }),
};

export default api;
