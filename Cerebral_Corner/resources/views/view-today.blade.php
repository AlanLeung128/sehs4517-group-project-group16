<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Today's Reservations | Cerebral Corner</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Slackey&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { 'slackey': ['Slackey', 'sans-serif'] },
                    colors: {
                        'brand-bg': '#F5EFE6',
                        'brand-text': '#4A3933',
                        'brand-header': '#3E2C1B',
                        'brand-header-text': '#F5EFE6',
                        'brand-title': '#8B4513',
                        'brand-subtitle': '#A0522D',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-brand-bg text-brand-text font-sans">

    <div class="max-w-6xl mx-auto p-8">

        <!-- Header -->
        <div class="flex justify-between items-center mb-10">
            <h1 class="text-4xl font-bold text-brand-title">My Reservations</h1>
            <div class="flex gap-4">
                <a href="/reservation" 
                   class="bg-brand-subtitle text-white px-6 py-3 rounded-3xl font-bold hover:bg-brand-title transition-all">
                    Make a Reservation
                </a>
                <a href="/logout" 
                   class="bg-gray-700 text-white px-6 py-3 rounded-3xl font-bold hover:bg-gray-600 transition-all">
                    Logout
                </a>
            </div>
        </div>

        <!-- My Reservations Section -->
        <div class="mb-12">
            <h2 class="text-3xl font-bold mb-6 flex items-center gap-3 text-brand-title">
                <i class="fas fa-user-clock"></i> My Upcoming Bookings
            </h2>
            <div id="myReservations" class="bg-white rounded-3xl p-8 shadow-sm border border-[#d4b89e]">
                <p id="myReservationsLoading" class="text-center text-gray-500 py-8">Loading your reservations...</p>
            </div>
        </div>

        <!-- Today's Reservations -->
        <div class="mb-10">
            <h2 class="text-3xl font-bold mb-6 flex items-center gap-3 text-brand-title">
                <i class="fas fa-calendar-day"></i> Today's Reservations
            </h2>
            <p class="text-brand-text mb-8">Today is {{ $today }} (Click on a space to view all time slots)</p>

            <!-- Legend -->
            <div class="flex gap-6 mb-8 text-sm">
                <div class="flex items-center gap-2"><div class="w-5 h-5 bg-red-200 rounded"></div> Past Time Slot</div>
                <div class="flex items-center gap-2"><div class="w-5 h-5 bg-gray-200 rounded"></div> Already Booked</div>
                <div class="flex items-center gap-2"><div class="w-5 h-5 bg-green-100 rounded"></div> Available</div>
            </div>

            <div class="grid grid-cols-4 gap-6" id="spaceGrid">
                @foreach(['Table 1 (4 pax Public)', 'Table 2 (4 pax Public)', 'Table 3 (4 pax Public)', 'Dragon Den (4 pax Private)', 'Wizard’s Corner (4 pax Private)', 'Epic Hall A (10 pax Private)', 'Epic Hall B (10 pax Private)', 'Legendary Lounge (16 pax Private)'] as $space)
                    <div onclick="showBookings('{{ $space }}')" 
                         class="bg-white p-6 rounded-3xl cursor-pointer hover:shadow-xl text-center border-2 border-transparent hover:border-brand-subtitle">
                        <div class="text-3xl mb-3">🪑</div>
                        <div class="font-bold text-lg">{{ $space }}</div>
                    </div>
                @endforeach
            </div>

            <div id="bookingDetail" class="hidden mt-12 bg-white rounded-3xl p-8 shadow-xl">
                <h2 class="text-2xl font-semibold mb-4">All Time Slots - <span id="selectedSpace" class="text-brand-title"></span></h2>
                <div id="bookedTimes" class="grid grid-cols-7 gap-3"></div>
            </div>
        </div>
    </div>

    <script>
        const reservations = @json($reservations);
        const myBookings = @json($myBookings ?? []);   

        const allTimeSlots = Array.from({length: 14}, (_, i) => {
            const start = (8 + i).toString().padStart(2, '0') + ':00';
            const end = (9 + i).toString().padStart(2, '0') + ':00';
            return `${start}-${end}`;
        });

        function renderMyBookings() {
            const container = document.getElementById('myReservations');
            const loading = document.getElementById('myReservationsLoading');

            if (myBookings.length === 0) {
                loading.innerHTML = `
                    <p class="text-center text-gray-500 py-12">
                        You have no upcoming reservations yet.<br>
                        <a href="/reservation" class="text-brand-subtitle underline">Book a table now →</a>
                    </p>`;
                return;
            }

            let html = `<div class="space-y-6">`;
            myBookings.forEach(booking => {
                const startTime = booking.time_slot;
                const endTime = new Date('2000-01-01 ' + startTime);
                endTime.setHours(endTime.getHours() + 1);
                const niceTime = startTime.replace(/^0/, '') + '-' + endTime.getHours() + ':00';

                html += `
                <div class="border border-[#d4b89e] rounded-2xl p-6 bg-white">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="font-bold text-xl">${booking.space_name}</div>
                            <div class="text-sm text-gray-500 mt-1">${booking.date} • ${niceTime}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs uppercase tracking-widest text-gray-400">Game</div>
                            <div class="font-medium">${booking.game || 'Not selected'}</div>
                        </div>
                    </div>
                </div>`;
            });
            html += `</div>`;

            container.innerHTML = html;
        }

        function showBookings(space) {
            document.getElementById('selectedSpace').textContent = space;
            const container = document.getElementById('bookedTimes');
            container.innerHTML = '';

            const bookedTimes = (reservations[space] || []).map(r => r.time_slot);
            const now = new Date();
            const currentHour = now.getHours();

            allTimeSlots.forEach(slot => {
                const hour = parseInt(slot.split(':')[0]);
                const isPast = hour < currentHour;
                const isBooked = bookedTimes.includes(slot);

                const div = document.createElement('div');
                div.className = `p-4 rounded-2xl text-center font-medium`;

                if (isPast) {
                    div.className += ' bg-red-200 text-red-700';
                    div.innerHTML = `${slot}<br><span class="text-xs">Past Time Slot</span>`;
                } else if (isBooked) {
                    div.className += ' bg-gray-200 text-gray-500 cursor-not-allowed';
                    div.innerHTML = `${slot}<br><span class="text-xs">Already Booked</span>`;
                } else {
                    div.className += ' bg-green-100 text-green-700';
                    div.textContent = slot;
                }
                container.appendChild(div);
            });

            document.getElementById('bookingDetail').classList.remove('hidden');
        }

        window.onload = () => {
            renderMyBookings();
        };
    </script>
</body>
</html>