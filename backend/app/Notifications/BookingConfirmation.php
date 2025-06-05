<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\VonageMessage;
use Illuminate\Notifications\Notification;

class BookingConfirmation extends Notification implements ShouldQueue
{
    use Queueable;

    protected $booking;

    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Return both channels or conditionally based on user preferences
        return ['mail', 'vonage'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Booking Confirmation')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your booking has been confirmed.')
            ->line('Booking Details:')
            ->line('Offer: ' . $this->booking->offer->title)
            ->line('Date: ' . $this->booking->booking_date->format('Y-m-d H:i'))
            ->line('Thank you for using our service!')
            ->action('View Booking', url('/bookings/' . $this->booking->id));
    }

    /**
     * Get the Vonage / SMS representation of the notification.
     */
    public function toVonage(object $notifiable): VonageMessage
    {
        return (new VonageMessage)
            ->content('Your booking for ' . $this->booking->offer->title . ' on ' . 
                      $this->booking->booking_date->format('Y-m-d H:i') . ' has been confirmed.');
    }
}
