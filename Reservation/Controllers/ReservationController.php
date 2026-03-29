<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservationController extends Controller
{
    // 顧客 - 預約頁面
    public function index()
    {
        return view('reservation');
    }

    // 顧客 - 預約提交
    public function store(Request $request)
    {
        $request->validate([
            'date'       => 'required|date',
            'time_slots' => 'required|array|min:1',
            'space_name' => 'required|string',
        ]);

        $date = Carbon::parse($request->date);

        foreach ($request->time_slots as $slot) {
            Reservation::create([
                'date'         => $date,
                'time_slot'    => $slot,
                'space_name'   => $request->space_name,
                'games'        => $request->games ?? [],
                'coffees'      => $request->coffees ?? [],
                'customer_id'  => null,   // 之後可接登入
                'total_amount' => $request->total_amount ?? 0,
            ]);
        }

        // 修正：明確使用 route name
        return redirect()->route('thankyou')
                        ->with('success', '預約成功！');
    }

    // 顧客 - 查看當日預約
    public function viewToday()
    {
        $today = Carbon::today()->format('Y-m-d');

        $reservations = Reservation::where('date', $today)
            ->get()
            ->groupBy('space_name')
            ->map(function ($items) {
                return $items->map(fn($item) => [
                    'time_slot' => $item->time_slot,
                    'customer'  => $item->customer_id ?? '未知客人',
                ]);
            });

        return view('view-today', compact('reservations', 'today'));
    }

    // Thank You Page
    public function thankyou()
    {
        return view('thankyou');
    }
}