API  VERSIONEMENT 


CONTROLLER / API / V1 / CONTROLLERS




FILAMENT  RESOURCES STATISTIQUES ECT ....

## Paiement

Pour simuler la logique de paiement, l'API offre un endpoint de traitement des paiements:

- `POST /api/v1/bookings/{bookingId}/pay`: Traite un paiement pour une réservation

### Paramètres de la requête:

```json
{
  "payment_method": "credit_card|paypal|bank_transfer",
  "card_token": "string (requis uniquement pour credit_card)"
}
```

### Réponse en cas de succès:

```json
{
  "success": true,
  "message": "Payment processed successfully",
  "booking": {
    "id": 1,
    "user_id": 5,
    "offer_id": 3,
    "booking_date": "2023-06-15",
    "booking_time": "14:30:00",
    "status": "confirmed",
    "payment_status": "paid",
    "payment_method": "credit_card",
    "transaction_id": "txn_XXXXXXXXXXXXXXXXXXXXXXXX",
    "paid_at": "2023-06-10T12:34:56.000000Z",
    "notes": null,
    "created_at": "2023-06-09T10:11:12.000000Z",
    "updated_at": "2023-06-10T12:34:56.000000Z"
  },
  "transaction_id": "txn_XXXXXXXXXXXXXXXXXXXXXXXX",
  "paid_at": "2023-06-10T12:34:56.000000Z"
}
```


