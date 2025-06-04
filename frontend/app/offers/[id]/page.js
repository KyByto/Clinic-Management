'use client';

import { useState, useEffect } from 'react';
import { useParams, useRouter } from 'next/navigation';
import { offerService, bookingService } from '../../services/api';
import { useAuth } from '../../context/AuthContext';
import Header from '../../components/Header';

export default function OfferDetails() {
  const params = useParams();
  const router = useRouter();
  const { isAuthenticated } = useAuth();
  const [offer, setOffer] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [bookingData, setBookingData] = useState({
    booking_date: '',
    booking_time: '',
    notes: ''
  });
  const [bookingError, setBookingError] = useState(null);
  const [bookingSuccess, setBookingSuccess] = useState(false);
  const [isSubmitting, setIsSubmitting] = useState(false);

  useEffect(() => {
    const fetchOffer = async () => {
      try {
        const data = await offerService.getOfferById(params.id);
        setOffer(data.data || null);
      } catch (err) {
        setError(err.message || 'Failed to fetch offer details');
      } finally {
        setLoading(false);
      }
    };

    if (params.id) {
      fetchOffer();
    }
  }, [params.id]);

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setBookingData(prev => ({ ...prev, [name]: value }));
  };

  const handleBooking = async (e) => {
    e.preventDefault();
    
    if (!isAuthenticated) {
      router.push('/login?redirect=' + encodeURIComponent(`/offers/${params.id}`));
      return;
    }

    setIsSubmitting(true);
    setBookingError(null);
    setBookingSuccess(false);

    try {
      await bookingService.createBooking({
        ...bookingData,
        offer_id: params.id
      });
      setBookingSuccess(true);
      setBookingData({
        booking_date: '',
        booking_time: '',
        notes: ''
      });
    } catch (err) {
      setBookingError(err.message || 'Failed to create booking');
    } finally {
      setIsSubmitting(false);
    }
  };

  if (loading) {
    return (
      <div className="min-h-screen flex flex-col">
        <Header />
        <div className="flex-grow flex justify-center items-center">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-700"></div>
        </div>
      </div>
    );
  }

  if (error || !offer) {
    return (
      <div className="min-h-screen flex flex-col">
        <Header />
        <div className="flex-grow flex justify-center items-center">
          <div className="bg-red-50 p-4 rounded-md">
            <div className="flex">
              <div className="flex-shrink-0">
                <svg className="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clipRule="evenodd" />
                </svg>
              </div>
              <div className="ml-3">
                <h3 className="text-sm font-medium text-red-800">Error loading offer</h3>
                <div className="mt-2 text-sm text-red-700">
                  <p>{error || 'Offer not found'}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen flex flex-col">
      <Header />
      
      <main className="flex-grow py-10">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="lg:grid lg:grid-cols-2 lg:gap-x-12">
            {/* Offer Details */}
            <div>
              <h1 className="text-3xl font-extrabold text-gray-900">{offer.title}</h1>
              
              <div className="mt-4">
                <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                  Available
                </span>
                <span className="ml-2 text-2xl font-bold text-gray-900">${offer.price}</span>
                <span className="text-sm text-gray-500 ml-1">per session</span>
              </div>
              
              <div className="mt-6">
                <h2 className="text-lg font-medium text-gray-900">Description</h2>
                <div className="mt-2 text-gray-600">
                  <p>{offer.description}</p>
                </div>
              </div>
              
              {offer.duration && (
                <div className="mt-6">
                  <h2 className="text-lg font-medium text-gray-900">Duration</h2>
                  <p className="mt-2 text-gray-600">{offer.duration} minutes</p>
                </div>
              )}

              {offer.clinic && (
                <div className="mt-6">
                  <h2 className="text-lg font-medium text-gray-900">Provider</h2>
                  <p className="mt-2 text-gray-600">{offer.clinic.name}</p>
                </div>
              )}
            </div>

            {/* Booking Form */}
            <div className="mt-10 lg:mt-0">
              <div className="bg-white p-6 border border-gray-200 rounded-lg shadow-sm">
                <h2 className="text-lg font-medium text-gray-900">Book this service</h2>
                
                {bookingSuccess ? (
                  <div className="mt-4 bg-green-50 border-l-4 border-green-400 p-4">
                    <div className="flex">
                      <div className="flex-shrink-0">
                        <svg className="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                          <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                        </svg>
                      </div>
                      <div className="ml-3">
                        <p className="text-sm text-green-700">
                          Booking successful! You can view your booking in "My Bookings".
                        </p>
                        <div className="mt-4">
                          <div className="flex">
                            <button
                              type="button"
                              onClick={() => router.push('/bookings')}
                              className="mr-2 bg-green-100 text-green-800 px-3 py-2 rounded-md text-sm font-medium"
                            >
                              View My Bookings
                            </button>
                            <button
                              type="button"
                              onClick={() => setBookingSuccess(false)}
                              className="bg-white text-gray-700 border border-gray-300 px-3 py-2 rounded-md text-sm font-medium"
                            >
                              Book Again
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                ) : (
                  <form onSubmit={handleBooking} className="mt-4 space-y-6">
                    {bookingError && (
                      <div className="bg-red-50 border-l-4 border-red-400 p-4">
                        <div className="flex">
                          <div className="flex-shrink-0">
                            <svg className="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                              <path fillRule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clipRule="evenodd" />
                            </svg>
                          </div>
                          <div className="ml-3">
                            <p className="text-sm text-red-700">{bookingError}</p>
                          </div>
                        </div>
                      </div>
                    )}

                    <div>
                      <label htmlFor="booking_date" className="block text-sm font-medium text-gray-700">Date</label>
                      <input
                        type="date"
                        name="booking_date"
                        id="booking_date"
                        required
                        min={new Date().toISOString().split('T')[0]}
                        value={bookingData.booking_date}
                        onChange={handleInputChange}
                        className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                      />
                    </div>

                    <div>
                      <label htmlFor="booking_time" className="block text-sm font-medium text-gray-700">Time</label>
                      <input
                        type="time"
                        name="booking_time"
                        id="booking_time"
                        required
                        value={bookingData.booking_time}
                        onChange={handleInputChange}
                        className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                      />
                    </div>

                    <div>
                      <label htmlFor="notes" className="block text-sm font-medium text-gray-700">Notes (optional)</label>
                      <textarea
                        name="notes"
                        id="notes"
                        rows="3"
                        value={bookingData.notes}
                        onChange={handleInputChange}
                        className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        placeholder="Any special requests or information"
                      ></textarea>
                    </div>

                    <div>
                      <button
                        type="submit"
                        disabled={isSubmitting}
                        className="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                      >
                        {isSubmitting ? 'Processing...' : 'Book Now'}
                      </button>
                    </div>

                    {!isAuthenticated && (
                      <p className="text-sm text-gray-500 mt-2">
                        You'll need to sign in before completing your booking.
                      </p>
                    )}
                  </form>
                )}
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  );
}
