/**
 * Service pour interagir avec l'API backend
 */
import axios from 'axios';

const API_URL = process.env.NEXT_PUBLIC_API_URL;

// Instance axios avec configuration de base
const api = axios.create({
  baseURL: API_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
});

// Intercepteur pour ajouter le token d'authentification
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token');
    if (token) {
      config.headers['Authorization'] = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Service d'authentification
export const authService = {
  register: async (userData) => {
    try {
      const response = await api.post('register', userData);
      if (response.data.access_token) {
        localStorage.setItem('token', response.data.access_token);
        localStorage.setItem('user', JSON.stringify(response.data.user));
      }
      return response.data;
    } catch (error) {
      console.log(error);
      throw error.response?.data || { message: 'Registration failed' };
    }
  },

  login: async (credentials) => {
    try {
      const response = await api.post('/auth/login', credentials);
      if (response.data.access_token) {
        localStorage.setItem('token', response.data.access_token);
        localStorage.setItem('user', JSON.stringify(response.data.user));
      }
      return response.data;
    } catch (error) {
      throw error.response?.data || { message: 'Login failed' };
    }
  },

  logout: async () => {
    try {
      await api.post('/auth/logout');
      localStorage.removeItem('token');
      localStorage.removeItem('user');
    } catch (error) {
      console.error('Logout error:', error);
    }
  },

  getCurrentUser: () => {
    const user = localStorage.getItem('user');
    return user ? JSON.parse(user) : null;
  },

  isAuthenticated: () => {
    return !!localStorage.getItem('token');
  }
};

// Service des offres
export const offerService = {
  getAllOffers: async () => {
    try {
      const response = await api.get('/v1/offers');
      return response.data;
    } catch (error) {
      throw error.response?.data || { message: 'Failed to fetch offers' };
    }
  },
  
  getOfferById: async (id) => {
    try {
      const response = await api.get(`/v1/offers/${id}`);
      return response.data;
    } catch (error) {
      throw error.response?.data || { message: 'Failed to fetch offer' };
    }
  }
};

// Service de réservations
export const bookingService = {
  createBooking: async (bookingData) => {
    try {
      const response = await api.post('/v1/bookings', bookingData);
      return response.data;
    } catch (error) {
      throw error.response?.data || { message: 'Booking failed' };
    }
  },
  
  getUserBookings: async () => {
    try {
      const response = await api.get('/v1/bookings');
      return response.data;
    } catch (error) {
      throw error.response?.data || { message: 'Failed to fetch bookings' };
    }
  },
  
  processPayment: async (bookingId) => {
    try {
      const response = await api.post(`/v1/bookings/${bookingId}/pay`);
      return response.data;
    } catch (error) {
      throw error.response?.data || { message: 'Payment failed' };
    }
  }
};

export default api;
