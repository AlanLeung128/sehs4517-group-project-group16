<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->format('Y-m-d');

        $bookedSlots = Reservation::where('date', $today)
            ->get()
            ->groupBy('space_name')
            ->map(fn($items) => $items->pluck('time_slot')->toArray());

        return view('reservation', compact('bookedSlots'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date'        => 'required|date|after_or_equal:today',
            'time_slots'  => 'required|array|min:1',
            'space'       => 'required|string',
            'game'        => 'nullable|string',
            'other_game'  => 'nullable|string',
            'phone'       => 'nullable|string|max:20',
        ]);

        $date = Carbon::parse($request->date);
        $game = $request->game === 'other' ? $request->other_game : $request->game;

        foreach ($request->time_slots as $slot) {
            Reservation::create([
                'date'         => $date,
                'time_slot'    => $slot,
                'space_name'   => $request->space,
                'game'         => $game,
                'phone'        => $request->phone,
                'customer_id'  => auth()->id() ?? null,
                'total_amount' => 0,
                'status'       => 'confirmed',
            ]);
        }

        $latestReservation = Reservation::latest()->first();

        return redirect()->route('thankyou')
                         ->with('reservation', $latestReservation);
    }

    public function viewToday()
    {
        $today = Carbon::today()->format('Y-m-d');

        $reservations = Reservation::where('date', $today)
            ->get()
            ->groupBy('space_name')
            ->map(function ($items) {
                return $items->map(fn($item) => [
                    'time_slot' => $item->time_slot,
                    'customer'  => $item->customer_id ? 'Customer #' . $item->customer_id : 'Walk-in',
                ]);
            });

        $myBookings = auth()->check() 
            ? Reservation::where('customer_id', auth()->id())
                ->whereIn('date', [
                    $today,
                    Carbon::tomorrow()->format('Y-m-d'),
                    Carbon::tomorrow()->addDay()->format('Y-m-d')
                ])
                ->orderBy('date')
                ->orderBy('time_slot')
                ->get()
            : collect();

        return view('view-today', compact('reservations', 'myBookings', 'today'));
    }

    public function thankyou()
    {
        return view('thankyou');
    }
}