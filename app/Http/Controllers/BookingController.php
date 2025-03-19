<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    /**
     * Create a new booking
     *
     * @param Request $request
     * @return JsonResponse
     */

     public function getBookings(Request $request): JsonResponse
     {
         $bookings = Booking::with([
             'customer',
             'vehicle.make',
             'vehicle.model',
             'pickupLocation',
             'deliveryLocation'
         ])->get();

         return response()->json($bookings);
     }

    public function createBooking(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'vehicleId' => 'required|exists:vehicles,id',
            'customerId' => 'required|exists:customers,id',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after:startDate',
            'deliveryLocationId' => 'required|exists:location,id',
            'pickupLocationId' => 'required|exists:location,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if vehicle is available for the selected dates
        $existingBookings = Booking::where('vehicleId', $request->vehicleId)
            ->where('status', '!=', 'cancelled')
            ->where(function($query) use ($request) {
                $query->whereBetween('startDate', [$request->startDate, $request->endDate])
                    ->orWhereBetween('endDate', [$request->startDate, $request->endDate])
                    ->orWhere(function($q) use ($request) {
                        $q->where('startDate', '<=', $request->startDate)
                          ->where('endDate', '>=', $request->endDate);
                    });
            })->count();

        if ($existingBookings > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Vehicle is not available for the selected dates'
            ], 409);
        }

        // Calculate the total price
        $vehicle = Vehicle::findOrFail($request->vehicleId);
        $startDate = new \DateTime($request->startDate);
        $endDate = new \DateTime($request->endDate);
        $days = $startDate->diff($endDate)->days + 1;
        $totalPrice = $vehicle->daily_rate * $days;

        // Create booking
        $booking = Booking::create([
            'vehicleId' => $request->vehicleId,
            'customerId' => $request->customerId,
            'startDate' => $request->startDate,
            'endDate' => $request->endDate,
            'deliveryLocationId' => $request->deliveryLocationId,
            'pickupLocationId' => $request->pickupLocationId,
            'total_price' => $totalPrice,
            'status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking created successfully',
            'data' => $booking
        ], 201);
    }
}
