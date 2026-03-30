<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Your Brain Time | Cerebral Corner</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Slackey&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'slackey': ['Slackey', 'sans-serif'],
                    },
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
<body class="bg-brand-bg text-brand-text font-sans leading-relaxed">

    <!-- Header -->
    <header class="bg-brand-header text-brand-header-text py-8">
        <div class="max-w-6xl mx-auto px-8 flex items-center justify-between">
            <div class="flex items-center gap-6">
                <img 
                    src="{{ asset('images/logo.png') }}" 
                    alt="Cerebral Corner Logo" 
                    class="w-16 h-16 rounded-full border-4 border-white shadow-lg">
                <div>
                    <h1 class="font-slackey text-4xl tracking-wide">Cerebral Corner</h1>
                    <p class="text-sm opacity-80">This is your brain playground.</p>
                </div>
            </div>
            
            <a href="/view-today" 
               class="bg-white text-brand-header px-6 py-3 rounded-3xl font-medium hover:bg-amber-100 transition-all">
                View Today's Reservations
            </a>
        </div>
    </header>

    <div class="max-w-6xl mx-auto p-8">

        <form action="{{ route('reservation.store') }}" method="POST" id="reservationForm">
            @csrf

            <!-- Date -->
            <div class="mb-10">
                <label class="block text-sm font-medium mb-3 text-brand-text">Reservation Date</label>
                <div class="flex gap-4">
                    <button type="button" onclick="setDate(1)" class="flex-1 py-5 text-lg font-semibold rounded-3xl border-2 border-brand-subtitle hover:bg-brand-subtitle hover:text-white date-btn transition-all">Tomorrow</button>
                    <button type="button" onclick="setDate(2)" class="flex-1 py-5 text-lg font-semibold rounded-3xl border-2 border-brand-subtitle hover:bg-brand-subtitle hover:text-white date-btn transition-all">Day After Tomorrow</button>
                </div>
                <input type="hidden" name="date" id="selectedDate">
            </div>

            <!-- Store Contact -->
            <div class="mb-10 bg-white p-6 rounded-3xl border border-[#d4b89e]">
                <p class="font-medium text-brand-text">For inquiries, please call the store:</p>
                <a href="tel:23456789" class="text-4xl font-bold text-brand-subtitle hover:underline">(852) 2345 6789</a>
            </div>

            <!-- Location -->
            <div class="mt-8">
                <h2 class="text-3xl font-bold mb-6 text-brand-title">Choose Your Brain Space</h2>
                <div class="grid grid-cols-4 gap-6" id="spaceGrid"></div>
            </div>

            <!-- Time -->
            <div class="mt-12">
                <h2 class="text-3xl font-bold mb-6 text-brand-title">Select Time Slots (Multiple allowed)</h2>
                <div id="timeSlotGrid" class="grid grid-cols-8 gap-4"></div>
            </div>

            <!-- Room Cost -->
            <div id="roomTotalSection" class="mt-12 hidden bg-white p-8 rounded-3xl border border-[#d4b89e]">
                <h3 class="text-2xl font-semibold mb-4 text-brand-title">Room Fee</h3>
                <div class="text-right text-3xl font-bold">
                    HK$ <span id="hourlyRateDisplay" class="text-brand-subtitle">80</span> per hour × 
                    <span id="selectedHours" class="text-brand-subtitle">0</span> slots = 
                    HK$ <span id="roomTotalPrice" class="text-brand-text">0</span>
                </div>
            </div>

            <!-- Board Game -->
            <div class="mt-12">
                <h2 class="text-3xl font-bold mb-6 text-brand-title">Choose a Board Game (One only)</h2>
                <div class="grid grid-cols-4 gap-6" id="gameGrid"></div>
                <div id="otherGameContainer" class="hidden mt-6">
                    <input type="text" name="other_game" placeholder="Enter other board game name" 
                           class="w-full p-5 border border-brand-subtitle rounded-3xl text-lg">
                </div>
            </div>

            <input type="hidden" name="space" id="selectedSpaceInput">

            <div class="mt-16 flex gap-6">
                <button type="submit" class="flex-1 bg-brand-header text-white py-6 text-xl font-bold rounded-3xl hover:bg-brand-title">Confirm Booking</button>
                <button type="button" onclick="clearAll()" class="flex-1 border-2 border-red-700 text-red-700 py-6 text-xl font-bold rounded-3xl hover:bg-red-50">Clear All</button>
            </div>
        </form>
    </div>

    <script>
        let selectedSpace = '';
        let dateSelected = false;

        const timeSlots = Array.from({length: 14}, (_, i) => {
            const start = (8 + i).toString().padStart(2, '0') + ':00';
            const end = (9 + i).toString().padStart(2, '0') + ':00';
            return {display: `${start}-${end}`, value: start};
        });

        const spaces = [
            {name: "Table 1 (4 pax Public)", rate: 80, desc: "Open brain corner, suitable for casual discussion"},
            {name: "Table 2 (4 pax Public)", rate: 80, desc: "Window-side thinking area with good lighting"},
            {name: "Table 3 (4 pax Public)", rate: 80, desc: "Classic public table, ideal for beginners"},
            {name: "Dragon Den (4 pax Private)", rate: 100, desc: "Mysterious dragon cave style with warm lighting"},
            {name: "Wizard’s Corner (4 pax Private)", rate: 100, desc: "Wizard corner surrounded by bookshelves"},
            {name: "Epic Hall A (10 pax Private)", rate: 200, desc: "Epic hall with wood and stone design"},
            {name: "Epic Hall B (10 pax Private)", rate: 200, desc: "Warm leather and brain map decoration"},
            {name: "Legendary Lounge (16 pax Private)", rate: 300, desc: "Large comfortable lounge with sofas"}
        ];

        const roomRates = {
            "Table 1 (4 pax Public)": 80, "Table 2 (4 pax Public)": 80, "Table 3 (4 pax Public)": 80,
            "Dragon Den (4 pax Private)": 100, "Wizard’s Corner (4 pax Private)": 100,
            "Epic Hall A (10 pax Private)": 200, "Epic Hall B (10 pax Private)": 200,
            "Legendary Lounge (16 pax Private)": 300
        };

        const allGamesWithDesc = [
            {name: "Catan", desc: "(Recommended 3-4 players, up to 6)"},
            {name: "Ticket to Ride", desc: "(Recommended 2-5 players)"},
            {name: "Wingspan", desc: "(Recommended 1-5 players, best with 2-4)"},
            {name: "Pandemic", desc: "(Recommended 2-4 players)"},
            {name: "Exploding Kittens", desc: "(Recommended 2-5 players, expandable to 10)"},
            {name: "D&D Starter Set", desc: "(Recommended 3-5 players + 1 DM, multiple teams possible)"}
        ];

        const roomRecommendations = {
            "Table 1 (4 pax Public)": ["Catan", "Ticket to Ride", "Wingspan"],
            "Table 2 (4 pax Public)": ["Catan", "Ticket to Ride"],
            "Table 3 (4 pax Public)": ["Ticket to Ride", "Pandemic"],
            "Dragon Den (4 pax Private)": ["Wingspan", "Pandemic", "Exploding Kittens"],
            "Wizard’s Corner (4 pax Private)": ["Wingspan", "D&D Starter Set"],
            "Epic Hall A (10 pax Private)": ["Exploding Kittens", "Pandemic"],
            "Epic Hall B (10 pax Private)": ["Catan", "Exploding Kittens"],
            "Legendary Lounge (16 pax Private)": ["D&D Starter Set", "Wingspan"]
        };

        function getRate(space) {
            return roomRates[space] || 80;
        }

        function setDate(days) {
            document.getElementById('selectedDate').value = new Date(Date.now() + days * 86400000).toISOString().split('T')[0];
            dateSelected = true;
            document.querySelectorAll('.date-btn').forEach(btn => btn.classList.remove('bg-amber-400', 'text-white'));
            event.target.classList.add('bg-amber-400', 'text-white');
            renderSpaces();
        }

        function renderSpaces() {
            const grid = document.getElementById('spaceGrid');
            if (!dateSelected) {
                grid.innerHTML = `<p class="col-span-4 text-center py-16 text-[#8c6f4f]">Please select a reservation date first</p>`;
                return;
            }
            grid.innerHTML = spaces.map(s => `
                <label onclick="selectSpace('${s.name}', this)" class="bg-white p-6 rounded-3xl border-2 border-transparent hover:border-[#8c6f4f] cursor-pointer transition-all group">
                    <div class="text-4xl mb-4">🪑</div>
                    <div class="font-bold text-xl mb-1">${s.name}</div>
                    <div class="text-sm text-gray-500 leading-tight">${s.desc}</div>
                </label>
            `).join('');
        }

        function selectSpace(space, el) {
            selectedSpace = space;
            document.querySelectorAll('#spaceGrid label').forEach(l => l.classList.remove('border-amber-400', 'bg-amber-100'));
            el.classList.add('border-amber-400', 'bg-amber-100');
            document.getElementById('selectedSpaceInput').value = space;

            renderTimeSlots();
            renderGamesForRoom(space);
            document.getElementById('roomTotalSection').classList.remove('hidden');
            calcRoomTotal();
        }

        function renderTimeSlots() {
            const grid = document.getElementById('timeSlotGrid');
            grid.innerHTML = timeSlots.map(t => `
                <label class="flex items-center justify-center p-3 border rounded-2xl hover:bg-amber-100 cursor-pointer">
                    <input type="checkbox" name="time_slots[]" value="${t.value}" class="mr-2"> ${t.display}
                </label>
            `).join('');

            const checkboxes = grid.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(cb => cb.addEventListener('change', calcRoomTotal));
        }

        function renderGamesForRoom(space) {
            const grid = document.getElementById('gameGrid');
            const recommended = roomRecommendations[space] || [];
            const recSet = new Set(recommended);

            const sortedGames = [
                ...recommended.map(name => allGamesWithDesc.find(g => g.name === name)),
                ...allGamesWithDesc.filter(g => !recSet.has(g.name))
            ];

            let html = `<div class="col-span-4 text-amber-700 mb-3 text-sm">Recommended games listed first</div>`;

            html += sortedGames.map(g => `
                <label class="p-4 border rounded-2xl text-center hover:bg-amber-100 cursor-pointer flex flex-col items-center">
                    <input type="radio" name="game" value="${g.name}" class="mb-2"> 
                    🎲 ${g.name} <span class="text-xs text-gray-500 mt-1">${g.desc}</span>
                </label>
            `).join('');

            html += `
                <label class="p-4 border rounded-2xl text-center hover:bg-amber-100 cursor-pointer flex items-center justify-center gap-2 col-span-4">
                    <input type="radio" name="game" value="other"> 🎲 Other Game
                </label>
            `;

            grid.innerHTML = html;

            const radios = grid.querySelectorAll('input[type="radio"]');
            radios.forEach(radio => {
                radio.addEventListener('change', () => {
                    const container = document.getElementById('otherGameContainer');
                    container.classList.toggle('hidden', radio.value !== 'other');
                    if (radio.value === 'other') container.querySelector('input').focus();
                });
            });
        }

        function calcRoomTotal() {
            if (!selectedSpace) return;
            const rate = getRate(selectedSpace);
            const checked = document.querySelectorAll('#timeSlotGrid input[type="checkbox"]:checked').length;
            const total = rate * checked;
            document.getElementById('hourlyRateDisplay').textContent = rate;
            document.getElementById('selectedHours').textContent = checked;
            document.getElementById('roomTotalPrice').textContent = total;
        }

        function clearAll() {
            if (confirm('Clear all selections?')) {
                document.getElementById('reservationForm').reset();
                selectedSpace = '';
                dateSelected = false;
                document.getElementById('selectedSpaceInput').value = '';
                document.getElementById('roomTotalSection').classList.add('hidden');
                document.getElementById('timeSlotGrid').innerHTML = '';
                document.getElementById('gameGrid').innerHTML = '';
                document.getElementById('otherGameContainer').classList.add('hidden');
                renderSpaces();
            }
        }

        window.onload = () => {
            renderSpaces();
        };
    </script>
</body>
</html>
</DOCUMENT>