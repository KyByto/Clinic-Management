<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\OfferResource;
use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    /**
     * Get a list of active offers
     * 
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        
        $offers = Offer::where('is_active', true)->get();
       

        return OfferResource::collection($offers);
    }

    /**
     * Get a specific offer by ID
     * 
     * @param int $id
     * @return OfferResource
     */
    public function show($id)
    {
        $offer = Offer::findOrFail($id);
        return new OfferResource($offer);
    }
}
