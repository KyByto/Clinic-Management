<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Process payment for a booking
     *
     * @param  int  $bookingId
     * @return \Illuminate\Http\JsonResponse
     */
    public function processPayment($bookingId)
    {
        try {
            // Trouver la réservation appartenant à l'utilisateur authentifié
            $booking = Booking::where('id', $bookingId)
                ->where('client_id', Auth::id())
                ->firstOrFail();
            
            // Vérifier si la réservation n'est pas déjà payée
            if ($booking->payment_status === 'paid') {
                return response()->json([
                    'success' => false,
                    'message' => 'This booking has already been paid for.'
                ], 400);
            }
            
            // Générer un ID de transaction aléatoire
            $transactionId = 'txn_' . Str::random(24);
            
            // Mettre à jour la réservation avec les détails de paiement
            $booking->update([
                'payment_status' => 'paid',
            ]);
            
            // Enregistrer l'événement de paiement
            Log::info('Payment processed', [
                'booking_id' => $booking->id,
                'client_id' => Auth::id(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Payment processed successfully',
                'booking' => $booking,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Payment processing failed', [
                'booking_id' => $bookingId,
                'client_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Payment processing failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
