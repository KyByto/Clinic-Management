<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Offer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Services\NotificationService;

class BookingController extends Controller
{
     protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
   

    public function index(Request $request)
    {
        // Récupérer les réservations de l'utilisateur authentifié
        $bookings = Booking::where('client_id', Auth::id())->get();

        return BookingResource::collection($bookings);
    }
    /**
     * Create a new booking
     * 
     * @param StoreBookingRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreBookingRequest $request)
    {
        try {
            // La validation de l'offre a déjà été faite dans StoreBookingRequest
            // Nous n'avons pas besoin de revérifier is_active ici

            // Create the booking
            $data = $request->validated();
            $data['client_id'] = auth()->id(); // Ajouter l'ID de l'utilisateur authentifié
            
            
            
            $booking = Booking::create($data);
            
            
            return (new BookingResource($booking))
                ->response()
                ->setStatusCode(201);
        } catch (\Exception $e) {
            \Log::error('Booking creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'Failed to create booking: ' . $e->getMessage()
            ], 500);
        }
    }
    public function sendNotification(Booking $booking)
    {
        $notificationSent = $this->notificationService->sendBookingConfirmation($booking);

        return response()->json([
            'success' => $notificationSent,
            'message' => $notificationSent ? 'Notification sent successfully' : 'Failed to send notification'
        ]);
    }

}
