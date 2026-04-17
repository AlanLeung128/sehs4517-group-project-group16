<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->format('Y-m-d');

        $bookedSlots = Reservation::where('date', '>=', $today)
            ->get()
            ->groupBy('space_name')
            ->map(fn($items) => $items->pluck('time_slot')->toArray())
            ->toArray();

        return view('reservation', compact('bookedSlots'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date'        => 'required|date|after_or_equal:today',
            'time_slots'  => 'required|array|min:1',
            'space'       => 'required|string',
        ]);

        $date = Carbon::parse($request->date);
        $game = $request->game === 'other' ? $request->other_game : $request->game;

        foreach ($request->time_slots as $slot) {
            $exists = Reservation::where('date', $date)
                ->where('space_name', $request->space)
                ->where('time_slot', $slot)
                ->exists();

            if ($exists) {
                $start = \Carbon\Carbon::parse($slot);
                $end   = $start->copy()->addHour();
                $niceTime = $start->format('g:i') . '-' . $end->format('g:i');

                $errorMsg = "⚠️ Booking Conflict!\n\n" .
                            "Room: {$request->space}\n" .
                            "Time Slot: {$niceTime}\n\n" .
                            "This time slot has already been booked by someone else.\n" .
                            "Please choose a different time.";

                return redirect()->back()
                    ->with('bookingError', $errorMsg);
            }
        }

        $createdReservations = [];

        foreach ($request->time_slots as $slot) {
            $createdReservations[] = Reservation::create([
                'date'         => $date,
                'time_slot'    => $slot,
                'space_name'   => $request->space,
                'game'         => $game,
                'phone'        => $request->phone ?? null,
                'customer_id'  => Session::get('user_id'),
                'total_amount' => 0,
                'status'       => 'confirmed',
            ]);
        }

        return redirect()->route('thankyou')
                         ->with('reservations', $createdReservations);
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

        $userId = Session::get('user_id');

        $myBookings = $userId 
            ? Reservation::where('customer_id', $userId)
                ->where('date', '>=', $today)
                ->orderBy('date')
                ->orderBy('time_slot')
                ->limit(12)
                ->get()
            : collect();

        return view('view-today', compact('reservations', 'myBookings', 'today'));
    }

  public function thankyou()
{
    $reservations = session('reservations');

    if (Auth::check()) {
        $email = Auth::user()->email;
    } else {
        $email = session('user_email') ?? 'Not logged in yet';
    }

    return view('thankyou', compact('reservations', 'email'));
}
}